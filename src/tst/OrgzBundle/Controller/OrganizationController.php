<?php

namespace tst\OrgzBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use tst\OrgzBundle\Entity\Organization;
use tst\OrgzBundle\Entity\User;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\File;




/**
 * Organization controller.
 *
 * @Route("/")
 */
class OrganizationController extends Controller
{
    /**
     * Lists all Organization entities.
     *
     * @Route("/")
     * @Route("/organization", name="organization_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createFormBuilder(['file' => ''])
            ->add('file', FileType::class, [
                'constraints' => [ new File([]) ],
            ])
            ->add('submit', SubmitType::class, ['label' => 'Импорт'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $logger = $this->get('logger');
                $aFilepath=$this->moveUploadedFiles($request->files,$logger);
                if(is_array($aFilepath)){
                    $validator=$this->get('validator');
                    $result=false;
                    foreach ($aFilepath as $filepath){
                        $result=$this->parseXmlFileAndSaveData($filepath, $em, $validator,$logger);
                        if(0>=$result){
                            $form->addError(new FormError($result));
                        }
                    }
                    unset($result,$filepath);
                }
                $form->addError(new FormError('Импорт завершен'));
            } else {
                $form->addError(new FormError('Что-то пошло не так'));
            }
        }

        $organizations = $em->getRepository('OrgzBundle:Organization')->findAll();

        return $this->render('OrgzBundle:organization:index.html.twig', array(
            'organizations' => $organizations,
            'form_import' => $form->createView(),
        ));
    }

    /**
     * Creates a new Organization entity.
     *
     * @Route("/new", name="organization_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $organization = new Organization();
        $form = $this->createForm('tst\OrgzBundle\Form\OrganizationType', $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($organization->getUsers() as $user)
            {
                $user->setOrganization($organization);
            }

            $em->persist($organization);
            $em->flush();

            return $this->redirectToRoute('organization_show', array('id' => $organization->getId()));
        }

        return $this->render('OrgzBundle:organization:new.html.twig', array(
            'organization' => $organization,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Organization entity.
     *
     * @Route("/{id}", name="organization_show")
     * @Method("GET")
     */
    public function showAction(Organization $organization)
    {
        $deleteForm = $this->createDeleteForm($organization);

        return $this->render('OrgzBundle:organization:show.html.twig', array(
            'organization' => $organization,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Organization entity.
     *
     * @Route("/{id}/edit", name="organization_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Organization $organization)
    {
        $originalUsers = new ArrayCollection();
        foreach ($organization->getUsers() as $user) {
            $originalUsers->add($user);
        }
        
        $deleteForm = $this->createDeleteForm($organization);
        $editForm = $this->createForm('tst\OrgzBundle\Form\OrganizationType', $organization);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();

            foreach ($organization->getUsers() as $user)
            {
                $user->setOrganization($organization);
            }

            foreach ($originalUsers as $user) {
                if (false === $organization->getUsers()->contains($user)) {
                    $em->remove($user);
                }
            }
            
            $em->persist($organization);
            $em->flush();

            return $this->redirectToRoute('organization_edit', array('id' => $organization->getId()));
        }

        return $this->render('OrgzBundle:organization:edit.html.twig', array(
            'organization' => $organization,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Organization entity.
     *
     * @Route("/{id}", name="organization_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Organization $organization)
    {
        $form = $this->createDeleteForm($organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($organization);
            $em->flush();
        }

        return $this->redirectToRoute('organization_index');
    }


    /**
     * Creates a form to delete a Organization entity.
     *
     * @param Organization $organization The Organization entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Organization $organization)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('organization_delete', array('id' => $organization->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * remove uploaded files
     * @param $files
     * @return array
     */
    private function moveUploadedFiles($files,$logger)
    {
        $filepath=[];
        if($files){
            foreach($files as $uploadedFile) {
                $file = $uploadedFile['file'];

                $fileName = md5(uniqid()).'.'.$file->guessExtension();
                $importDir = $this->container->getParameter('kernel.root_dir').'/../web/uploads/';
                try {
                    if(!is_dir($importDir)){
                        mkdir($importDir,0755,1);
                    }
                    $file->move($importDir, $fileName);
                } catch (FileException $e) {
                    $logger->error($e->getMessage());
                }

                $filepath[] = $importDir . DIRECTORY_SEPARATOR . $fileName;
            }
        }
        return $filepath;
    }

    /**
     * @param $filePath
     * @param $em
     * @param $validator
     * @param $logger
     * @return bool|string
     */
    private function parseXmlFileAndSaveData($filePath,  $em, $validator, $logger)
    {
        $response=0;
        try {
            $aXml = self::parseXmlFile($filePath);

            if ($aXml['org'] && is_array($aXml['org'])) {
                foreach ($aXml['org'] as $orgInfo) {
                    $errors = [];
                    if (isset($orgInfo['@attributes'])) {

                        $dataOrganization = $orgInfo['@attributes'];
                        $organization = new Organization();
                        $organization->setTitle($dataOrganization['displayName']);
                        $organization->setOgrn(1*$dataOrganization['ogrn']);
                        $organization->setOktmo(1*$dataOrganization['oktmo']);
                        $errors = $validator->validate($organization);
                        if (0 === count($errors) ) {
                            $em->persist($organization);
                            $response++;

                            if (isset($orgInfo['user']) && is_array($orgInfo['user'])) {
                                foreach ($orgInfo['user'] as $userInfo) {
                                    $userInfo =  isset($userInfo['@attributes']) ? $userInfo['@attributes'] : $userInfo;

                                    $user = new User();

                                    $user->setSecondname($userInfo['lastname']);
                                    $user->setFirstname($userInfo['firstname']);
                                    $user->setDateBirth(isset($userInfo['date_birth']) ? $userInfo['date_birth'] : null);
                                    $user->setInn($userInfo['inn']);
                                    $user->setPatronymic($userInfo['middlename']);
                                    $user->setSnils($userInfo['snils']);
                                    $user->setOrganization($organization);
                                    $errors = $validator->validate($user);

                                    if (0 === count($errors) ) {
                                        $em->persist($user);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($response > 0) {
                $em->flush();
            }else{
                $response='Не добавленно не одной организации';
                throw new \Exception($response);
            }

        } catch (\Exception $e) {
            $response=$e->getMessage();
            $logger->error($e->getMessage());
        }
        return $response;
    }



    /**
     * parse XML file to array
     * @param $file
     * @return mixed
     */
    static private function parseXmlFile($file)
    {
        // включение обработки ошибок
        libxml_use_internal_errors(true);

        // загрузка документа
        $xml = simplexml_load_file($file, "SimpleXMLElement");

        if (!$xml) {

            libxml_clear_errors();
            throw new \Exception('Файл с ошибкой');
        }

        $json = json_encode($xml);
        return json_decode($json,1);
    }
}
