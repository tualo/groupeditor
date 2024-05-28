Ext.define('Tualo.groupeditor.lazy.Viewport',{
    extend: 'Ext.panel.Panel',
    requires:[
        'Tualo.groupeditor.lazy.controller.Viewport',
        'Tualo.groupeditor.lazy.model.Viewport',
    ],
    alias: 'widget.groupeditorviewport',
    
    tools: [{
        xtype: 'glyphtool',
        //glyphPrefix: 'entypo et-',
        glyph: 'circle-plus',
        tooltip: 'Hinzufügen',
        handler: function (me) {
            var grid = this.up('panel').down('grid');
            var store = grid.getStore();
            store.add({name:'Neue Gruppe',aktiv:1});
            setTimeout(()=>{store.load()},1000);

        }
    },
    {
        xtype: 'glyphtool',
        //glyphPrefix: 'entypo et-',
        glyph: 'circle-minus',
        tooltip: 'Entfernen',
        handler: function (me) {
            var grid = this.up('panel').down('grid');
            var store = grid.getStore();
            var selection = grid.getSelection()[0];
            if (!selection) return;
            
            
            
            Ext.MessageBox.confirm('Löschen','Soll der Eintrag "'+selection.get('name')+'" wirklich gelöscht werden?', function (btn) {
                if (btn == 'yes') {

                    Tualo.Fetch.post('groupeditor/delete', {
                        id: selection.get('name'),
                    }).then(function (result) {
                        store.load();
                    }).catch(function (e) {
                        Ext.Msg.alert('Fehler', e);
                    });
                }
            });
        }


    },
    {
        xtype: 'glyphtool',
        //glyphPrefix: 'typcn typcn-arrow-',
        glyph: 'sync',
        tooltip: 'neu Laden',
        handler: function (me) {
            var grid = this.up('panel').down('grid');
            var store = grid.getStore();
            store.load( );
        }

    }],
    layout: 'card',
    items: [
        {
            xtype: 'grid',
            selModel: 'cellmodel',
            plugins: {
                cellediting: {
                    clicksToEdit: 1
                }
    },
            store: {
                type: 'json',
                autoSync: true,
                autoLoad: true,
                proxy: {
                    type: 'ajax',
                    api: {
                        create: './groupeditor/create',
                        read: './groupeditor/read',
                        update: './groupeditor/update',
                        destroy: './groupeditor/delete'
                    },
                    reader: {
                        type: 'json',
                        rootProperty: 'data',
                        idProperty: 'name'
                    }
                }
            },
            
            listeners: {
                itemdblclick: function (me, record, item, index, e, eOpts) {
                    this.up('panel').getLayout().setActiveItem(1);
                    var form = this.up('panel').down('form');
                    form.loadRecord(record);
                }
            },
            columns: [{
                text: 'Name',
                editor: 'textfield',
                dataIndex: 'name',
                flex: 2,
                sortable: true
            },{
                xtype: 'checkcolumn',
                text: 'Aktiv',
                dataIndex: 'aktiv',
                flex: 1,
                sortable: true,
            }]
        },{
            xtype: 'form',
            bodyPadding: 10,
            defaults: {
                anchor: '100%',
                labelWidth: 120
            },
            
            buttons: [
                {
                    text: 'Abbrechen',
                    handler: function (me) {
                        var form = me.up('form');
                        form.up('panel').getLayout().setActiveItem(0);
                    },
                },'->',
                {
                    text: 'Speichern',
                    handler: function (me) {
                        var form = me.up('form');
                        var grid = form.up('panel').down('grid');
                        var store = tree.getStore();
                        var root = store.getRoot();
                        var selection = grid.getSelection()[0];
                        if (!selection) selection = root;
                        let vals = form.getValues();


                        if (Ext.isEmpty(vals.groups)) vals.groups = [];
                        selection.set('text',vals.text);
                        selection.set('title',vals.text);
                        // selection.set('iconCls',vals.iconCls);
                        selection.set('iconcls',vals.iconcls);
                        selection.set('route_to',vals.route_to);
                        selection.set('groups',vals.groups);
                        selection.commit();

                        form.up('panel').getLayout().setActiveItem(0);

                    }
                }
            ],
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: 'Name',
                    name: 'name',
                    allowBlank: false
                },
                {
                    xtype: 'checkbox',
                    fieldLabel: 'Aktiv',
                    name: 'aktiv',
                    allowBlank: true
                }
            ]
        }
    ]
} );