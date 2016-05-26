(function () {
    'use strict';


    // setup an "add a tag" link
    var addUserLink = $('<a href="#" class="add_user_link">Добавить пользователя</a>');
    var $newLinkLi = $('<li></li>').append(addUserLink);

    jQuery(document).ready(function() {
        $("form").on('click',"a.deleteRowLink", function() {
            $(this).closest('.row').remove(); });
        // Get the ul that holds the collection of tags
        var $collectionHolder = $('ul.users');

        // add the "add a tag" anchor and li to the tags ul
        $collectionHolder.append($newLinkLi);

        // count the current form inputs we have (e.g. 2), use that as the new
        // index when inserting a new item (e.g. 2)
        $collectionHolder.data('index', $collectionHolder.find(':input').length);

        addUserLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see code block below)
            addUserForm($collectionHolder, $newLinkLi);
        });


    });

    function addUserForm($collectionHolder, $newLinkLi) {
        // Get the data-prototype explained earlier
        var prototype = $collectionHolder.data('prototype');

        // get the new index
        var index = $collectionHolder.data('index');

        // Replace '$$name$$' in the prototype's HTML to
        // instead be a number based on how many items we have
        var newForm = prototype.replace(/__name__/g, index);

        // increase the index with one for the next item
        $collectionHolder.data('index', index + 1);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li class="pos_relative clearfix"></li>').append(newForm);

        // also add a remove button, just for this example
        $newFormLi.append('<a class="btn btn-default deleteRowLink remove-user" href="#"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>');

        $newLinkLi.before($newFormLi);

        // handle the removal, just for this example
        $('.remove-user').click(function(e) {
            e.preventDefault();

            $(this).parent().remove();

            return false;
        });
    }

})();