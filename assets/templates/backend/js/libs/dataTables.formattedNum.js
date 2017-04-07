jQuery.extend( jQuery.fn.dataTableExt.oSort['formatted-num-asc'] = function(a,b) {
    /* Remove any formatting */
    var x = a.match(/\d/) ? a.replace( /[^\d\-\.]/g, "" ) : 0;
    var y = b.match(/\d/) ? b.replace( /[^\d\-\.]/g, "" ) : 0;
      
    /* Parse and return */
    return parseFloat(x) - parseFloat(y);
},
  
jQuery.fn.dataTableExt.oSort['formatted-num-desc'] = function(a,b) {
    var x = a.match(/\d/) ? a.replace( /[^\d\-\.]/g, "" ) : 0;
    var y = b.match(/\d/) ? b.replace( /[^\d\-\.]/g, "" ) : 0;
      
    return parseFloat(y) - parseFloat(x);
} );
/*jQuery.extend( jQuery.fn.dataTableExt.aTypes.unshift(  
    function ( sData )  
    {  
        var deformatted = sData.replace(/[^\d\-\.\/a-zA-Z]/g,'');
        if ( $.isNumeric( deformatted ) ) {
            return 'formatted-num';
        }
        return null;  
    }  
) );
jQuery.extend( jQuery.fn.dataTableExt.oSort, {
    "formatted-num-pre": function ( a ) {
        a = (a==="-") ? 0 : a.replace( /[^\d\-\.]/g, "" );
        return parseFloat( a );
    },
 
    "formatted-num-asc": function ( a, b ) {
        return a - b;
    },
 
    "formatted-num-desc": function ( a, b ) {
        return b - a;
    }
} );*/