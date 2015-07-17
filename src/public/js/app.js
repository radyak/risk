var UI = {
    
    error : function(message, title, closeLabel){
        
        var config = {
            type : "error",
            message : message,
            title : title || "Error",
            buttons : {
                close: {
                    label : closeLabel || "OK"
                }
            }
        };
        this.errorDialog = new Dialog(config);
        
    },
    
    warn : function(message, title, closeLabel){
        
        var config = {
            type : "warn",
            message : message,
            title : title || "Warning",
            buttons : {
                close: {
                    label : closeLabel || "OK"
                }
            }
        };
        this.warnDialog = new Dialog(config);
        
    },
    
    info : function(message, title, closeLabel){
        
        var config = {
            type : "info",
            message : message,
            title : title || "Information",
            buttons : {
                close: {
                    label : closeLabel || "OK"
                }
            }
        };
        this.infoDialog = new Dialog(config);
        
    }
    
    
};

var HTML = {

    body : $(document.body),
    
    make : function(tagname, id, classes, type){
        var element = $(document.createElement(tagname))
                .attr("id", id)
                .attr("class", classes)
                .attr("type", type);
        return element;
    }
    
};

$(document).ready(function(){

    $('input[type="userselector"]').each(function(index, value){
        var element = $(value);
        new UserSelector(element);
    });
    
    $('select').each(function(index, value){
        var element = $(value);
        new DropDown(element);
    });

    $('input[type="checkbox"]').each(function(index, value){
        var element = $(value);
        new CheckBox(element);
    });
    
    /*setTimeout(function(){
        $(".alert-success").fadeOut(1000, function(){
            $(".alert-success").hide();
        });
    }, 3000);*/
});


function CheckBox(baseInput) {
    
    var selfpointer = this;
    
    this._checked;
    var iconCheckedSrc = "/img/checkbox-true.png";
    var iconUncheckedSrc = "/img/checkbox-false.png";
    baseInput.hide();
    this._checkIcon = HTML.make("img", "", "icon");
    
    this.set = function(check){
        if(check){
            this._checked = true;
            this._checkIcon.attr("src", iconCheckedSrc);
            baseInput.attr("checked", "true");
        } else {
            this._checked = false;
            this._checkIcon.attr("src", iconUncheckedSrc);
            baseInput.removeAttr("checked");
        }
    };
    
    this.set( (baseInput.attr("checked")) );
    
    baseInput.after(this._checkIcon);
    
    this._checkIcon.click(function(){
        selfpointer.set(!selfpointer._checked);
    });
    
    var id = baseInput.attr("id");
    var label = $("label[for='" + id + "']");
    label.click(function(){
        selfpointer.set(!selfpointer._checked);
    });
}

function DropDown(baseInput){
    
    baseInput.hide();
    var dropDown = HTML.make("div", baseInput.attr("id"), "dropdown");
    baseInput.after(dropDown);
    
    this.selectedOption = null;
    var selfpointer = this;
    this.options = baseInput.find("option");
    
    var button = HTML.make("button", "", "dropdown-toggle")
            .attr("aria-expanded", "true")
            .attr("data-toggle", "dropdown")
            .attr("aria-expanded", "true");
    this._buttonLabel = HTML.make("span", "", "dropdown-label");
    var buttonCaret = HTML.make("span", "", "caret");
    button
            .append(this._buttonLabel)
            .append(buttonCaret);
    
    var list = HTML.make("ul", "", "dropdown-menu")
            .attr("role", "menu");
    
    this._hidden = HTML.make("input")
            .attr("name", baseInput.attr("name"))
            .attr("type", "hidden");
    dropDown.append(button);
    dropDown.append(list);
    dropDown.append(this._hidden);
    
    this.select = function (option) {
        this.selectedOption = option;
        if(option.value){
            this._hidden.val(option.value);
        } else {
            this._hidden.val(option.label);
        }
        this._buttonLabel.html(option.label);
    };
    
    var hasIcons = false;
    this.options.each(function(index, value){
        if($(value).attr("icon")){
            hasIcons = true;
        }
    });
    
    this.options.each(function(index, value){
        var option = value;
        if(option.selected){
            selfpointer.select(option);
        }
        var li = HTML.make("li")
                .attr("role", "presentation");
        if(hasIcons){
            var icon = HTML.make("img", "", "drop-down-icon").attr("src", $(option).attr("icon"));
            li.append(icon);
        }
        li.append(option.label);
        list.append(li);
        li.click({option: option}, function(event){
            selfpointer.select(event.data.option);
        });
    });
    
    baseInput.remove();
    
}

function Dialog(config){
    
    /*
    Example Config:
    
        var config = {
            allowCloseOnBackground : false,
            type : "error",
            message : message,
            title : title || "Error",
            buttons : {
                close: {
                    label : closeLabel || "OK",
                    callback : function(){
                        alert("Clooose");
                    }
                },
                action : {
                    label : "Action",
                    callback : function(){
                        alert("Yay");
                    }
                }
            },
            textInput : {
                id : "myInput",
                textAfter : "After Input"
            },
            timeouts : {
                dialogTimeout : 3000,
                dialogFadeDuration : 300
            }
        };
    */
    
    if(!Dialog._initiated){
        Dialog._stack = HTML.make("div", "modal");
        Dialog._background = HTML.make("div", "modal-background");
        Dialog._stack.append(Dialog._background);
        HTML.body.prepend(Dialog._stack);
        Dialog._initiated = true;
    }
    this._header = HTML.make("div", "", "header");
    this._body = HTML.make("div", "", "body");
    this._footer = HTML.make("div", "", "footer");
    Dialog._dialog = HTML.make("div", "modal-dialog")
            .append(this._header)
            .append(this._body)
            .append(this._footer)
            .appendTo(Dialog._stack);
    this._closer = HTML.make("button", "", "close").html("&times;");
    this._title = HTML.make("span", "", "title");
    this._header.append(this._closer);
    this._header.append(this._title);
    
    
    this._close = HTML.make("button", "", "btn btn-primary");
    this._action = HTML.make("button", "", "btn btn-primary");
    this._footer.append(this._close);
    this._footer.append(this._action);
    
    
    var type = config.type;
    var message = config.message;
    var title = config.title;
    var textInput = config.textInput;
    var buttons = config.buttons;
    var timeouts = config.timeouts;
    
    var self = this;
    
    this.close = function(){
        Dialog._stack.hide();
    };
    if(config.allowCloseOnBackground){
        Dialog._background.click(function(){
            self.close();
        });
    }
    this._closer.click(function(){
        self.close();
    });
    
    Dialog._dialog.attr("class", type);
    //type must be "error", "warn", "info"
    
    this._title.html(title);
    this._body.html(message);
    
    if(textInput){
        this._textInput = HTML.make("input", textInput.id, "", "text");
        this._body.append(this._textInput);
        if(textInput.textAfter){
            this._body.append(textInput.textAfter);
        }
    }
    
    if(buttons){
        if(buttons.close){
            self._close.html(buttons.close.label);
            self._close.click(function(){
                if(buttons.close.callback){
                    buttons.close.callback();
                }
                self.close();
            });
            self._close.show();
        } else {
            self._close.hide();
        }
        
        if(buttons.action){
            self._action.html(buttons.action.label);
            self._action.click(function(){
                if(buttons.action.callback){
                    buttons.action.callback();
                }
                self.close();
            });
            self._action.show();
        } else {
            self._action.hide();
        }
    } else {
        self._close.hide();
        self._action.hide();
    }
    
    if(timeouts){
        var dialogTimeout = timeouts.dialogTimeout || 3000;
        var dialogFadeDuration = timeouts.dialogFadeDuration || 400;
        setTimeout(function(){
            Dialog._dialog.fadeOut(dialogFadeDuration, function(){
                Dialog._stack.hide();
            });
        }, dialogTimeout);
    }
    
    Dialog._stack.show();
}

function UserSelector(baseInput){
    
    baseInput.addClass("loading");
    
    baseInput.wrap("<div class='userselector-wrapper'></div>");
    
    var marginTop = 7;
    
    this.sourceURL = "/json/users/names";
    this.usernames = new Array();
    var _selfpointer = this;
    
    var namesDisplay = HTML.make("div");
    var parameterName = baseInput.attr("name");
    baseInput.removeAttr("name");
    var hidden = HTML.make("input")
            .attr("name", parameterName)
            .attr("type", "hidden");
    baseInput.after(namesDisplay);
    baseInput.after(hidden);
    
    var _split = function( val ) {
        return val.split( /,\s*/ );
    };
    var _extractLast = function( term ) {
        return _split( term ).pop();
    };
    
    this.removeUserName = function ( userName ){
        var tempNames = new Array();
        for(var i = 0; i < this.usernames.length; i++){
            var currentUserName = this.usernames[i];
            if(currentUserName !== userName){
                tempNames.push(currentUserName);
            }
        }
        this.usernames = tempNames;
        hidden.val(this.usernames.join(","));
        this.refreshUserNames();
    };
    
    this.addUserName = function ( userName ){
        if(this.usernames.indexOf(userName) == -1){
            this.usernames.push(userName);
        }
        this.refreshUserNames();
    };
    
    this.refreshUserNames = function (){
        
        namesDisplay.empty();
        for(var i = 0; i < this.usernames.length; i++){
            var userName = this.usernames[i];
            if(userName !== ""){
                var nameDisplay = HTML.make("div")
                        .attr("class", "usernamedisplay")
                        .html(userName);
                var removeSymbol = HTML.make("span")
                        .attr("class", "removesymbol")
                        .html("&times;");
                removeSymbol.click({userName: userName}, function(event){
                    _selfpointer.removeUserName(event.data.userName);
                });
                nameDisplay.append(removeSymbol);
                namesDisplay.append(nameDisplay);
            }
        }
        hidden.val(this.usernames.join(","));
        
    };
    
    $.get(this.sourceURL, function(usernames){
        
        baseInput
            // don't navigate away from the field on tab when selecting an item
            .bind( "keydown", function( event ) {
                if ( event.keyCode === $.ui.keyCode.TAB && $( this ).autocomplete( "instance" ).menu.active ) {
                    event.preventDefault();
                }
                if ( event.keyCode === $.ui.keyCode.ENTER ) {
                    event.preventDefault();
                }
            })

            .autocomplete({
                
                position: {
                    my: "left top" + marginTop
                },

                source: usernames,

                search: function() {
                    var term = _extractLast( this.value );
                },

                focus: function() {
                    // prevent value inserted on focus
                    return false;
                },

                select: function( event, ui ) {
                    var terms = _split( this.value );

                    // remove the current input
                    terms.pop();
                    // add the selected item
                    terms.push( ui.item.value );
                    // add placeholder to get the comma-and-space at the end
                    terms.push( "" );
                    baseInput.val("");
                    _selfpointer.addUserName( ui.item.value );

                    return false;
                }
            });
        baseInput.removeClass("loading");
    });
}