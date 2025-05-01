/**
 * Hjandles widgets scripts
 * 
 * @package Newsis
 * @since 1.0.0
 */
jQuery(document).ready( function($) {
    function newsis_widgets_handler() {
        // multicheckbox field
        $( ".newsis-multicheckbox-field" ).on( "click, change", ".multicheckbox-content input", function() {
            var _this = $(this), parent = _this.parents( ".newsis-multicheckbox-field" ), currentVal, currentFieldVal = parent.find( ".widefat" ).val();
            currentFieldVal = JSON.parse( currentFieldVal )
            currentVal = _this.val();
            if( _this.is(":checked") ) {
                if( currentFieldVal != 'null' ) {
                    currentFieldVal.push(currentVal)
                }
            } else {
                if( currentFieldVal != 'null' ) {
                    currentFieldVal.splice( $.inArray( currentVal, currentFieldVal ), 1 );
                }
            }
            parent.find( ".widefat" ).val(JSON.stringify(currentFieldVal))
        })

        // checkbox field
        $( ".newsis-checkbox-field" ).on( "click, change", "input", function() {
            var _this = $(this)
            if( _this.is(":checked") ) {
                _this.val( "1" )
            } else {
                _this.val( "0" )
            }
        })

        // upload field
        $( ".newsis-upload-field" ).on( "click", ".upload-trigger", function(event) {
            event.preventDefault();
            if ( frame ) {
                frame.open();
                return;
            }
            var _this = $(this), frame = wp.media({
                title: 'Select or Upload Author Image',
                button: {
                    text: 'Add Author Image'
                },
                multiple: false
            });
            frame.open();
            frame.on( 'select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                console.log( attachment )
                _this.toggleClass( "selected not-selected" );
                _this.next().toggleClass( "selected not-selected" );
                _this.next().find("img").attr( "src", attachment.url ).toggleClass( "nothasImage" );
                _this.siblings(".widefat").val( attachment.id ).trigger("change");
            })
        })
        // remove image
        $( ".newsis-upload-field" ).on( "click", ".upload-buttons .remove-image", function(event) {
            event.preventDefault();
            var _this = $(this);
            _this.prev().attr( "src", "" ).toggleClass( "nothasImage" );
            _this.parent().toggleClass( "selected not-selected" ).prev().toggleClass( "selected not-selected" );
            _this.parent().next().val( "" ).trigger("change");
        })

        // icon text handler
        var iconTextContainer = $( ".newsis-icon-text-field" )
        iconTextContainer.each(function() {
            var _this = $(this), iconSelector = _this.find( ".icon-selector-wrap" ), iconField = _this.find( ".icon-field" ), textField = _this.find( ".text-field input" )
            iconSelector.on( "click", "i", function() {
                var newIcon = $(this).attr( "class" )
                iconField.data( "value", newIcon )
                iconField.find( ".icon-selector i" ).removeClass().addClass(newIcon)
                setIconTextFieldValue(_this,iconField,textField)
            })
            textField.on( "change", function() {
                setIconTextFieldValue(_this,iconField,textField)
            })
            iconField.each(function(){
                $(this).on( "click", function() {
                    var innerThis = $(this)
                    innerThis.siblings(".icon-selector-wrap" ).toggleClass('isactive')
                })
            })
        })

        function setIconTextFieldValue(el,iconEl,txtEl) {
            el.find( 'input.widefat[type="hidden"]' ).val(JSON.stringify( {icon: iconEl.data( "value" ), title: txtEl.val()} )).trigger("change")
        }

        // repeater field handler
        var repeaterContainer  = $(".newsis-repeater-field")
        if( repeaterContainer.length > 0 ) {
            repeaterContainer.each(function() {
                var _this = $(this)
                _this.on( "change", ".single-field-form-field", function(event) {
                    event.preventDefault()
                    $(this).val( event.target.value )
                    renderRepeaterValue(_this)
                })

                // on item edit area toggle
                _this.on( "click", ".single-item-heading", function(event) {
                    event.preventDefault()
                    var _thisHeading = $(this), parentItem = _thisHeading.parent()
                    _thisHeading.find(".heading-icon i").toggleClass("fa-chevron-up fa-chevron-down")
                    _thisHeading.next().toggle()
                    parentItem.siblings().find(".single-item-heading .heading-icon i").removeClass("fa-chevron-up").addClass("fa-chevron-down")
                    parentItem.siblings().find(".single-item-edit-area").slideUp()
                })

                // on add item
                _this.on( "click", ".field-actions .add-item", function(event) {
                    event.preventDefault()
                    var addFieldButton = $(this)
                    var parentContainer = addFieldButton.parents(".newsis-repeater-field"), newItemToRender = parentContainer.find( ".repeater-field-content-area" ), newItem = parentContainer.find( ".repeater-field-content-area .repeater-single-item" ).last().clone()
                    parentContainer.find( ".repeater-field-content-area .repeater-single-item .single-item-heading .heading-icon i" ).removeClass("fa-chevron-up").addClass("fa-chevron-down")
                    parentContainer.find( ".repeater-field-content-area .repeater-single-item .single-item-edit-area" ).slideUp()
                    newItem.find(".single-field-form-field").val("")
                    newItem.find(".image-field .remove-image").addClass("hide")
                    newItem.find(".image-field .upload-image").removeClass("hide")
                    newItem.find(".image-field img").attr("src","")
                    newItem.find(".single-item-heading .heading-icon i").removeClass("fa-chevron-down").addClass("fa-chevron-up")
                    // newItem.find(".single-item-edit-area").addClass("isShow")
                    newItem.find(".single-item-edit-area").slideDown()
                    newItemToRender.append(newItem)
                    renderRepeaterValue(_this)
                })

                // on item remove
                _this.on( "click", ".single-item-actions .remove-item", function(event) {
                    event.preventDefault()
                    var removeItemButton = $(this)
                    removeItemButton.parents(".repeater-single-item").remove()
                    renderRepeaterValue(_this)
                })

                // trigger image upload
                _this.on( "click", ".image-field .upload-image", function(event) {
                    event.preventDefault();
                    var uploadButton = $(this)
                    if ( frame ) {
                        frame.open();
                        return;
                    }
                    var frame = wp.media({
                        title: 'Select or Upload Image',
                        button: {
                            text: 'Add Image'
                        },
                        multiple: false
                    });
                    frame.open();
                    frame.on( 'select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        uploadButton.addClass("hide")
                        uploadButton.next().removeClass("hide")
                        uploadButton.prev().attr( "src", attachment.url );
                        uploadButton.parent().next().val( attachment.id );
                        renderRepeaterValue(_this)
                        frame.close();
                    })
                })

                _this.on( "click", ".image-field .remove-image", function(event) {
                    event.preventDefault();
                    var removeButton = $(this)
                    removeButton.addClass("hide")
                    removeButton.prev().removeClass("hide")
                    removeButton.parent().find("img").attr("src","")
                    removeButton.parent().next().val(0);
                    renderRepeaterValue(_this)
                })
            })

            function renderRepeaterValue(parentContainer) {
                var elementToRenderValue = parentContainer.find(".widefat"), items = parentContainer.find(".repeater-single-item"), newRepeaterValue = []
                if( items.length > 0 ) {
                    items.each(function() {
                        var _thisItem = $(this), fields = _thisItem.find(".single-field-form-field"), newItemValue = {}
                        if( fields.length > 0 ) {
                            fields.each(function() {
                                var _thisField = $(this), fieldName = _thisField.data("name"), fieldValue = _thisField.val()
                                newItemValue[fieldName] = fieldValue
                            })
                        }
                        newRepeaterValue.push(newItemValue)
                    })
                    elementToRenderValue.val(JSON.stringify(newRepeaterValue)).trigger("change")
                }
            }
        }
    }
    newsis_widgets_handler();
    
    // run on widgets added and updated
    $( document ).on( 'load widget-added widget-updated', function() {
        newsis_widgets_handler();
    });
})