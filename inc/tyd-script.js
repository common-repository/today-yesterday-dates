jQuery(document).ready(function($) {
    $('.tcode').textillate({
        loop: true,
        minDisplayTime: 5000,
        initialDelay: 800,
        autoStart: true,
        inEffects: [],
        outEffects: [],
        in: {
            effect: 'rollIn',
            delayScale: 1.5,
            delay: 50,
            sync: false,
            shuffle: true,
            reverse: false,
            callback: function() {}
        },
        out: {
            effect: 'fadeOut',
            delayScale: 1.5,
            delay: 50,
            sync: false,
            shuffle: true,
            reverse: false,
            callback: function() {}
        },
        callback: function() {}
    });
})
jQuery(document).ready(function($) {
var date = jQuery('#jdate');
var adddate = jQuery('#addtimetodate');
var moddate = jQuery('#jmoddate');
var addmoddate = jQuery('#addtimetomoddate');
var select = this.value;

if (jQuery('#jdate').val() == 'enabled') {
    jQuery('.addtimetodatetr').show();
    jQuery('.delimiterdatetr').show();
} else {
    jQuery('.addtimetodatetr').hide(); 
    jQuery('.delimiterdatetr').hide(); 
}
if (jQuery('#jmoddate').val() == 'enabled') {
    jQuery('.addtimetomoddatetr').show();
    jQuery('.delimitermoddatetr').show();
} else {
    jQuery('.addtimetomoddatetr').hide(); 
    jQuery('.delimitermoddatetr').hide(); 
}
if (jQuery('#addtimetodate').is(":checked") & jQuery('#jdate').val() == 'enabled') {
    jQuery('.delimiterdatetr').show();
} else {
    jQuery('.delimiterdatetr').hide(); 
}
if (jQuery('#addtimetomoddate').is(":checked") & jQuery('#jmoddate').val() == 'enabled') {
    jQuery('.delimitermoddatetr').show();
} else {
    jQuery('.delimitermoddatetr').hide(); 
}

date.change(function () {
    if (jQuery(this).val() == 'enabled') {
        jQuery('.addtimetodatetr').fadeIn();
        if (jQuery('#addtimetodate').is(":checked")) {
            jQuery('.delimiterdatetr').fadeIn();
        }
    } else { 
        jQuery('.addtimetodatetr').hide();
        jQuery('.delimiterdatetr').hide();
    }
});
adddate.change(function () {
    if (jQuery('#addtimetodate').is(":checked")) {
        jQuery('.delimiterdatetr').fadeIn();
    } else { 
        jQuery('.delimiterdatetr').hide(); 
    }
});

moddate.change(function () {
    if (jQuery(this).val() == 'enabled') {
        jQuery('.addtimetomoddatetr').fadeIn();
        if (jQuery('#addtimetomoddate').is(":checked")) {
            jQuery('.delimitermoddatetr').fadeIn();
        }
    } else { 
        jQuery('.addtimetomoddatetr').hide();
        jQuery('.delimitermoddatetr').hide();
    }
});
addmoddate.change(function () {
    if (jQuery('#addtimetomoddate').is(":checked")) {
        jQuery('.delimitermoddatetr').fadeIn();
    } else { 
        jQuery('.delimitermoddatetr').hide(); 
    }
});

})