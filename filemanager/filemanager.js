varienGrid.prototype.doFilter = function () {
    var filters = $$('#' + this.containerId + ' .filter input', '#' + this.containerId + ' .filter select');
    var elements = [];
    this.addVarToUrl('path', btoa($F('folder_select')));
    for (var i in filters) {
        if (filters[i].value && filters[i].value.length) elements.push(filters[i]);
    }
   // console.log(elements); // Log the filter elements to   they are being collected
    if (!this.doFilterCallback || (this.doFilterCallback && this.doFilterCallback())) {
        this.reload(this.addVarToUrl(this.filterVar, encode_base64(Form.serializeElements(elements))));
    }
};
varienGrid.prototype.resetFilter = function () {
    this.addVarToUrl('path', btoa($F('folder_select')));
    this.reload(this.addVarToUrl(this.filterVar, ''));
},

varienGrid.prototype.doSort = function (event) {
    var element = Event.findElement(event, 'a');
    this.addVarToUrl('path', btoa($F('folder_select')));
    if (element.name && element.title) {
        this.addVarToUrl(this.sortVar, element.name);
        this.addVarToUrl(this.dirVar, element.title);
        this.reload(this.url);
    }
    Event.stop(event);
    return false;
},
varienGrid.prototype.loadByElement = function(element) {
    var folderSelect = $('folder_select');
    if (folderSelect) {
        this.addVarToUrl('path', btoa($F(folderSelect)));
    }
    if (element && element.name && element.value) {
        this.reload(this.addVarToUrl(element.name, element.value));
    }
};


varienGrid.prototype.setPage = function(pageNumber) {
    this.addVarToUrl('path', btoa($F('folder_select')));
    this.reload(this.addVarToUrl(this.pageVar, pageNumber));
};




document.observe("dom:loaded", function () {
    initializeGrid();

    $$('.action-download').invoke('observe', 'click', function (event) {
        var element = event.element();
        var filePath = element.getAttribute('data-filepath');
        var url = element.getAttribute('href') + '?filePath=' + encodeURIComponent(filePath);
        window.location.href = url;
        event.stop();
    });

    $$('.action-delete').invoke('observe', 'click', function (event) {
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
    deliveryNoteColumns.each(function (element) {
        element.observe("click", function (event) {
            if (!element.hasClassName("editing")) {
                element.addClassName("editing");
                var oldFileName = element.innerText.trim();
                element.innerHTML = "";

                var text = new Element("input", { type: "text", value: oldFileName });
                element.appendChild(text);
                text.focus();

                var saveButton = new Element("button").update("✔");
                saveButton.observe("click", function () {
                    var newValue = text.value.trim();
                    handleDeliveryNoteClick(oldFileName, newValue, element.getAttribute("data-url"), element.getAttribute("data-filepath"));
                });
                element.appendChild(saveButton);

                var closeButton = new Element("button").update("✖");
                closeButton.observe("click", function () {
                    element.update(oldFileName);
                    element.removeClassName("editing");
                });
                element.appendChild(closeButton);

                document.observe("click", function (event) {
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
    console.log(oldFileName);
    console.log(newValue);
    console.log(filePath);
    console.log(url);
    var formKey = FORM_KEY

    new Ajax.Request(url, {
        method: 'post',
        parameters: {
            oldFilename: oldFileName,
            newFilename: newValue,
            filePath: filePath,
            form_key: formKey
        },
        onSuccess: function (response) {
            console.log(response);
            var result = response.responseText.evalJSON();
            console.log(result);
            if (result.status === 'success') {
                console.log(result.status);
                alert(result.message);
                // location.reload();
            } else {

                alert(result.message);
            }
        },
        onFailure: function () {
            alert('An error occurred while renaming the file.');
        }
    });
}

