
uploader = {
    tests: {
        filereader: typeof FileReader != 'undefined',
        dnd: 'draggable' in document.createElement('span'),
        formdata: !!window.FormData,
        progress: "upload" in new XMLHttpRequest
    },
    acceptedTypes: {
        'text/plain': true,
        'text/csv': true,
        'text/xml': true
    },
    xhr: [],
    readfiles: function (files, progress, url, key) {
        
        var formData = this.tests.formdata ? new FormData() : null;
        if (files.length > 1) {
            alert("Multiple files upload is not supported.");
            return;
        }
        for (var i = 0; i < files.length; i++) {
//            if (this.acceptedTypes[files[i].type] !== true) {
//                alert("Wrong file type. Xml, csv or txt files only.\n" + files[i].name + " can't be uploaded.");
//            } else {
                if (this.tests.formdata) {

                    formData.append('file_upload', files[i]);
                    formData.append('form_key', key);
                    uploader.xhr[i] = new XMLHttpRequest();
                   
                    uploader.xhr[i].open('POST', url);
                   
                    if (this.tests.progress) {
                        uploader.xhr[i].upload.onprogress = function (event) {
                            if (event.lengthComputable) {
                                complete = (event.loaded / event.total * 100 | 0);
                                progress.value = progress.innerHTML = complete;
                            }
                        };
                        uploader.xhr[i].onload = function (response) {

                            try {
                                progress.value = progress.innerHTML = 0;
                                r = (this.response.evalJSON())
                                if (r.error === true) {
                                    alert(r.message);
                                } else {

                                    $("file_path").value = r.message;
                                    $$("BUTTON.updateOnChange")[0].removeClassName("disabled");
                                }
                            } catch (err) {
                                alert("Error:".err.message);
                            }
                        }
                    }

                    uploader.xhr[i].send(formData);
                }
//            }
        }
    },
    initialize: function (holder, progress, url, key) {
        if (this.tests.dnd) {
            holder.ondragover = function () {
                this.addClassName('hover');
                return false;
            };
            holder.ondragend = function () {
                this.removeClassName('hover');
                return false;
            };
            holder.ondrop = function (e) {
                this.removeClassName('hover');
                e.preventDefault();
                uploader.readfiles(e.dataTransfer.files, progress, url, key);
            };
        }
    }
};