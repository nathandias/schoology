<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@9.1.1/dist/css/autoComplete.min.css">
</head>

<body>
    <div class="autoComplete_wrapper">
        <input type="search" dir="ltr" spellcheck=false autocorrect="off" autocomplete="off" autocapitalize="off" id="autoComplete">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tarekraafat/autocomplete.js@9.1.1/dist/js/autoComplete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

    <script>
    
        $.getJSON('/terms/', function(data) {
            new autoComplete({
                selector: "#autoComplete",
                placeHolder: "What are you looking for...",
                data: {
                    src: data.terms
                },
                resultsList: {
                    noResults: (list, query) => {
                        // Create "No Results" message element
                        const message = document.createElement("div");
                        // Add class to the created element
                        message.setAttribute("class", "no_result");
                        // Add message text content
                        message.innerHTML = `<span>Found No Results for "${query}"</span>`;
                        // Append message element to the results list
                        list.appendChild(message);
                    },
                },
                resultItem: {
                    highlight: {
                        render: true
                    }
                }
            });
        });      

    </script>
</body>

</html>