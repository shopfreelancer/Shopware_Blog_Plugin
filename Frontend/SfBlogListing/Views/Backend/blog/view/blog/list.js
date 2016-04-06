//{block name="backend/blog/view/blog/list" append}
//{namespace name=backend/blog/view/blog}


Ext.define('Shopware.apps.Blog.view.list.List.its.List', {

    /**
     * Defines an override applied to a class.
     * @string
     */
    override: 'Shopware.apps.Blog.view.blog.List',

    /**
     * Overrides the getColumns function of the overridden ExtJs object
     * and inserts two new columns
     * @return
     */
    getColumns: function() {
        var me = this;
        var columns = me.callParent(arguments);

        var columnCat =            {
            header:'Category',
            dataIndex:'categoryName',
            flex:1
        }
    console.log(columns);
        columns.splice(2,2);

        return Ext.Array.insert(columns, 2, [columnCat]);
    }
});
//{/block}

//{block name="backend/blog/model/main/fields" append}
{ name : 'categoryName', type : 'string' },
//{/block}