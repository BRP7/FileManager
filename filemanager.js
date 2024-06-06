varienGrid.prototype.doFilter = function () {

    var filters = $$('#' + this.containerId + ' .filter input', '#' + this.containerId + ' .filter select');
    var elements = [];
    // console.log(btoa($F('folder_select')));
    this.addVarToUrl('path', btoa($F('folder_select')));
    for (var i in filters) {
        if (filters[i].value && filters[i].value.length) elements.push(filters[i]);
    }
    // console.log(elements);
    if (!this.doFilterCallback || (this.doFilterCallback && this.doFilterCallback())) {
        this.reload(this.addVarToUrl(this.filterVar, encode_base64(Form.serializeElements(elements))));
    }
},

    varienGrid.prototype.resetFilter = function () {
        this.addVarToUrl('path', btoa($F('folder_select')));
        this.reload(this.addVarToUrl(this.filterVar, ''));
    }



document.observe("dom:loaded", function() {
    initializeGrid();

    $$('.action-download').invoke('observe', 'click', function(event) {
        var element = event.element();
        var filePath = element.getAttribute('data-filepath');
        var url = element.getAttribute('href') + '?filePath=' + encodeURIComponent(filePath);
        window.location.href = url;
        event.stop();
    });

    $$('.action-delete').invoke('observe', 'click', function(event) {
        if (!confirm('Are you sure you want to delete this file?')) {
            event.stop();
            return;
        }

        var element = event.element();
        var filePath = element.getAttribute('data-filepath');
        var url = element.getAttribute('href') + '?filePath=' + encodeURIComponent(filePath);
        window.location.href = url;
        event.stop();
    });
});

function initializeGrid() {
    var deliveryNoteColumns = $$(".editableDiv");
    deliveryNoteColumns.each(function(element) {
        element.observe("click", function(event) {
            if (!element.hasClassName("editing")) {
                element.addClassName("editing");
                var oldFileName = element.innerText.trim();
                element.innerHTML = "";

                var text = new Element("input", { type: "text", value: oldFileName });
                element.appendChild(text);
                text.focus();

                var saveButton = new Element("button").update("✔");
                saveButton.observe("click", function() {
                    var newValue = text.value.trim();
                    handleDeliveryNoteClick(oldFileName, newValue, element.getAttribute("data-url"), element.getAttribute("data-filepath"));
                });
                element.appendChild(saveButton);

                var closeButton = new Element("button").update("✖");
                closeButton.observe("click", function() {
                    element.update(oldFileName);
                    element.removeClassName("editing");
                });
                element.appendChild(closeButton);

                document.observe("click", function(event) {
                    if (!element.contains(event.target)) {
                        element.update(oldFileName);
                        element.removeClassName("editing");
                    }
                });
            }
        });
    });
}

function handleDeliveryNoteClick(oldFileName, newValue, url, filePath) {
    new Ajax.Request(url, {
        method: 'post',
        parameters: {
            oldFilename: oldFileName,
            newFilename: newValue,
            filePath: filePath
        },
        onSuccess: function(response) {
            var result = response.responseText.evalJSON();
            if (result.status === 'success') {
                alert(result.message);
                location.reload();
            } else {
                alert(result.message);
            }
        },
        onFailure: function() {
            alert('An error occurred while renaming the file.');
        }
    });
}
