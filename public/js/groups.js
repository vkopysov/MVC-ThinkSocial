(function () {
    var TRUE = 1;
    var FALSE = 0;
    var GROUP_CREATION_HANDLER = "/groups/add";
    var GROUP_SUBSCRIBE_HANDLER = "/groups/subscribe/";
    var GROUP_UNSUBSCRIBE_HANDLER = '/groups/unsubscribe/';
    var GROUP_CREATION_TIMER = 60000;
    var UNSUBSCRIBE_BUTTON_CLASS = 'w3-red';
    var SUBSCRIBE_BUTTON_CLASS = 'w3-indigo';
    var CREATION_STAMP = TRUE;
    var offsetY = 0;
    var offsetX = 0;
    var hidden = 'sg-hidden';
    var POST_MAX_SIZE = 5000000;
    var groupId = /id([0-9]+)$/;

    var panel = {},
        newsSubmit = {},
        dropBox = {},
        postNewsPanel = {},
        groupCreationForm = {},
        avatarUploader;


    var checkNode = function (node) {
        if (!node) {
            return false;
        } else {
            return node;
        }
    };

    var eventSubscriber = function (node, event, func) {
        if (!node) {
            return false;
        }
            node.addEventListener(event, func);
    };

    var preventAditionRequest = function () {
        if (!CREATION_STAMP) {
            return false;
        }
        CREATION_STAMP = FALSE;
        setTimeout(function () {
            CREATION_STAMP = TRUE;
        }, GROUP_CREATION_TIMER);
    };

    var actionGroupUnSubscribe = function (event) {
        event.preventDefault();
        var that = this;
        if (groupId.test(this.href)){
            var id = groupId.exec(this.href)[0];
            var request = new XMLHttpRequest();
            request.open('POST', this.href, true);
            request.setRequestHeader('Content-type', "application/x-www-form-urlencoded");
            request.send();
            request.onreadystatechange = function () {
                if (this.readyState == 4) {
                    if (this.status == 204) {
                        that.classList.remove(UNSUBSCRIBE_BUTTON_CLASS);
                        that.classList.add(SUBSCRIBE_BUTTON_CLASS);
                        that.href = GROUP_SUBSCRIBE_HANDLER + id;
                        that.innerHTML = "Subscribe";
                        that.removeEventListener('click', actionGroupUnSubscribe);
                        that.addEventListener('click', actionGroupSubscribe);
                    }
                }
            }
        } else {
            return false;
        }
    };

    var actionGroupSubscribe = function (event) {
        event.preventDefault();
        var that = this;
        if (groupId.test(this.href)){
            var id = groupId.exec(this.href)[0];
            var request = new XMLHttpRequest();
            request.open('POST', this.href, true);
            request.setRequestHeader('Content-type', "application/x-www-form-urlencoded");
            request.send();
            request.onreadystatechange = function () {
                if (this.readyState == 4) {
                    if (this.status == 204) {
                        that.classList.remove(SUBSCRIBE_BUTTON_CLASS);
                        that.classList.add(UNSUBSCRIBE_BUTTON_CLASS);
                        that.href = GROUP_UNSUBSCRIBE_HANDLER + id;
                        that.innerHTML = "Unsubscribe";
                        that.removeEventListener('click', actionGroupSubscribe);
                        that.addEventListener('click', actionGroupUnSubscribe);
                    }
                }
            }
        } else {
            return false;
        }
    };


    var actionGroupCreate = function (event) {
        event.preventDefault();
        preventAditionRequest();
        var form = this;
        var messageBoxSuccess = document.createElement('div');
        messageBoxSuccess.classList.add('create-group-msg-box-success');
        var messageBoxError = document.createElement('div');
        messageBoxError.classList.add('create-group-msg-box-error');
        var response = "";

        var groupName = this.getElementsByClassName('group-create-input')[0].value;
        var groupDescription = this.getElementsByClassName('create-group__describe')[0].value;
        var message = 'group-name=' + groupName + "&description=" + groupDescription;


        var request = new XMLHttpRequest();
        request.open('POST', GROUP_CREATION_HANDLER, true);
        request.setRequestHeader('Content-type', "application/x-www-form-urlencoded");
        request.send(message);
        request.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 201) {
                    response = JSON.parse(this.responseText);
                    messageBoxSuccess.innerHTML = response.message;
                    form.insertBefore(messageBoxSuccess, form.firstChild);
                    setTimeout(function () {
                        location.href = response.url;
                    }, 1000);
                }
            }
        }

    };

    var fileUpload = function()
    {

    };

    var showParange = function(event){
        event.preventDefault();
        offsetY = window.pageYOffset;
        var parange = document.getElementById('parange');
        parange.style['display'] = "block";
        document.body.classList.add('bodyflow');
        var groupName = document.getElementsByName('group-name');
        groupName[0].focus();
    };

    var closeParange = function() {
        document.body.classList.remove('bodyflow');
        document.getElementById('parange').style['display'] = null;
        window.scroll(offsetX, offsetY);
    };

    var showNewsAdder = function () {
        newsSubmit.classList.remove(hidden);
    };

    var hideNewsAdder = function (event) {
        if(!panel) {
            return false;
        }
        var node = event.target;
        var input = document.getElementsByClassName('post-news-input')[0];
        var documentChecker = function (node) {
          if (node == panel) {
            return false;
          } else if (node != document) {
              documentChecker(node.parentNode);
          } else {
              if (input.value == '') {
                newsSubmit.classList.add(hidden);
              }
          }
        };
        documentChecker(node);
    };

    var dropBoxOver = function (event) {
        event.preventDefault();
        dropBox.classList.add('hover');
    };

    var dropBoxLeave = function () {
        dropBox.classList.remove('hover');
    };

    var dropBoxUpload = function (event) {
        event.preventDefault();
        var file = event.dataTransfer.files[0];
        if (file.size > POST_MAX_SIZE) {

        } else {
            var formData = new FormData();
            formData.append("myfile", file);
            var xhr = new XMLHttpRequest();

            xhr.upload.onprogress = function(event) {
                console.log(event.loaded + ' / ' + event.total);
            };

            xhr.onload = xhr.onerror = function() {
                if (this.status == 200) {
                   console.log("success");
                } else {
                    console.log("error " + this.status);
                }
            };
            xhr.open("POST", "http://ts.local/groups/page/id15", true);
            xhr.setRequestHeader("X_PAGE_ACTION", "AVATAR_UPLOAD");
            xhr.send(formData);
        }
        console.log(file);
    };


    var init = function () {
        panel = checkNode(document.getElementsByClassName('post-news-panel')[0]);
        newsSubmit = checkNode(document.getElementsByClassName('post-news-submit')[0]);
        dropBox = checkNode(document.getElementsByClassName('avatar-drop-box')[0]);
        postNewsPanel = checkNode(document.getElementsByClassName('post-news-panel')[0]);
        groupCreationForm = checkNode(document.getElementsByClassName('form-bag')[0]);

        var groupBtns = document.getElementsByClassName('group-card__subs');
        for (var i = 0; i < groupBtns.length ; i++) {
            if (groupBtns[i].classList.contains(UNSUBSCRIBE_BUTTON_CLASS)) {
                eventSubscriber(groupBtns[i], 'click', actionGroupUnSubscribe);
            } else if (groupBtns[i].classList.contains(SUBSCRIBE_BUTTON_CLASS)) {
                eventSubscriber((groupBtns[i]), 'click', actionGroupSubscribe);
            }
        }


        eventSubscriber(document.getElementById('create-group-btn'), 'click', showParange);
        eventSubscriber(document.getElementById('create-group-close'), 'click', closeParange);
        eventSubscriber(postNewsPanel, 'click', showNewsAdder);
        eventSubscriber(document, 'click', hideNewsAdder);
        eventSubscriber(dropBox, 'dragover', dropBoxOver);
        eventSubscriber(dropBox, 'dragleave', dropBoxLeave);
        eventSubscriber(dropBox, 'drop', dropBoxUpload);
        eventSubscriber(groupCreationForm, 'submit', actionGroupCreate)
    };

    var onWindowLoad = function () {
        window.addEventListener('load', init);
    };

    onWindowLoad();
})();
