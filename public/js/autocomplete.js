/**
 * jQuery UI AutoComplete for textbook search
 * http://api.jqueryui.com/autocomplete/#option-source
 */

$(document).ready(function() {

    $('#autocompleteBuy').autocomplete({
        source: function(request, response) {
            $.ajax({
                url: location.protocol + '//' + document.domain + '/textbook/searchAutoComplete',
                dataType: 'json',
                data: {
                    term: request.term,
                    university_id: $('.search-input-university').val()
                },
                success: function(data) {
                    console.log(data);
                    response(data);
                }
            })
        },
        minLength: 3,
        focus: function(event, ui) {
            // prevent updating input
            event.preventDefault();
        },
        select: function(event, ui) {
            // prevent updating input
            event.preventDefault();
            // go to the book's url
            window.location.href = "/textbook/buy/" + ui.item.id;
        }
    }).autocomplete('instance')._renderItem = function(ul, item) {
        // TODO: use default image if no image.
        var image = '#';

        if (item.image) {
            image = item.image;
        }

        var authors = 'Unknown';

        if (item.authors && item.authors.length > 0) {
            authors = item.authors.join(', ');
        }

        return $("<li>")
            .append(
                '<div class="autocomplete-result">' +
                    '<div class="autocomplete-thumbnail">' +
                        '<img src="' + image + '">' +
                    '</div>' +
                    '<div class="autocomplete-data">' +
                        '<div class="autocomplete-title">' + item.title + '</div>' +
                        '<div class="autocomplete-authors">' + authors + '</div>' +
                        '<div class="autocomplete-isbn">ISBN-10:' + item.isbn10 + '</div>' +
                        '<div class="autocomplete-isbn">ISBN-13:' + item.isbn13 + '</div>' +
                    '</div>' +
                '</div>')
            .appendTo(ul);
    };;
});