import Grid from '../../classes/grid';

Grid.extend(function(){
    Grid.extendComponent({
        data: function() {
            return {
                selectedItems: [],
                allSelected: false,
            };
        },
        watch: {
            selectedItems: function(newValue, oldValue) {
                if(newValue.length === this.items.length) {
                    this.allSelected = true;
                } else {
                    this.allSelected = false;
                }
            },
        },
        methods: {
            selectAll: function() {
                this.selectedItems = [];
                if(this.allSelected === true) {
                    return;
                }
                for(var i = 0; i < this.items.length; i++) {
                    this.selectedItems.push(this.items[i]);
                }
            },
            clearSelection: function() {
                this.selectedItems.splice(0, this.selectedItems.length);
            },

            select: function(item) {
                if(this.isSelected(item)) {
                    var index = this.selectedItems.indexOf(item);
                    this.selectedItems.splice(index, 1);
                } else {
                    this.selectedItems.push(item);
                }

            },
            isSelected: function(item) {
                return this.selectedItems.indexOf(item) >= 0;
            }
        },
    });
});