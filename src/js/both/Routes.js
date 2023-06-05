Ext.Loader.setPath('Tualo.groupeditor.lazy', './jsgroupeditor');

Ext.define('Tualo.routes.GroupEditor',{
    statics: {
        load: async function() {
            return [
                {
                    name: 'groupeditor',
                    path: '#groupeditor'
                }
            ]
        }
    },  
    url: 'groupeditor',
    handler: {
        action: function( ){
            Ext.getApplication().addView('Tualo.groupeditor.lazy.Viewport');
        },
        before: function (action) {
            action.resume();
        }
    }
});