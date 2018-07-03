import Grid from '../../classes/grid';

Grid.extend(function(){
    Grid.extendComponent({
        data: function(){
            return {
                editing: [],
            };
        },
        methods: {
            isInlineEditable: function(item) {
                for(var k in this.columns) {
                    if(this.columns[k].editable) {
                        return true;
                    }
                }
                return false;
            },
            isEditingItem: function(item) {
                for(var i = 0; i < this.editing.length; i++) {
                    if(this.editing[i].item === item) {
                        return true;
                    }
                }
                return false;
            },
            isEditing: function(item, column) {
                if(!column.editable) {
                    return false;
                }
                return this.isEditingItem(item);
            },
            cancelEdit: function(item, column)
            {
                var index = null;
                var copy = null;
                for(var i = 0; i < this.editing.length; i++) {
                    if(this.editing[i].item === item) {
                        index = i;
                        copy = this.editing[i].itemCopy;
                        break;
                    }
                }
                for(var k in copy) {
                    item[k] = copy[k];
                }

                Vue.delete(this.editing, index);
            },
            saveEdit: function(item, column) {
                var index = null;
                var copy = null;
                for(var i = 0; i < this.editing.length; i++) {
                    if(this.editing[i].item === item) {
                        index = i;
                        copy = this.editing[i].itemCopy;
                        break;
                    }
                }

                var updatedData = {};
                updatedData[this.config.identifier] = item[this.config.identifier];


                for(var k in this.columns) {

                    var column = this.columns[k];

                    if(!column.editable) {
                        continue;
                    }

                    updatedData[column.property] = item[column.property];
                }

                Vue.delete(this.editing, index);
                this.$emit('update-item', updatedData);

            },
            edit: function(item, column) {

                var itemCopy = {};
                for(var k in item) {
                    itemCopy[k] = item[k];
                }

                this.editing.push(
                    {item: item, itemCopy: itemCopy, column: column}
                );
            },
        },
    });
});