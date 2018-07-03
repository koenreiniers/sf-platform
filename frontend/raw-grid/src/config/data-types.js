import { GridDataTypes } from 'raw-grid/extensions';

GridDataTypes.dataType('datetime', {
    inputType: 'datetime-local',
    operators: [
        '==', '>=', '>'
    ],
    defaultOperator: '=='
});

GridDataTypes.dataType('string', {
    operators: [
        '==', '!=', 'CONTAINS'
    ],
    defaultOperator: 'CONTAINS',
});

GridDataTypes.dataType('integer', {
    inputType: 'number',
    operators: [
        '==', '!=', '>=', '<=', '<', '>', 'BETWEEN',
    ],
    defaultOperator: '==',
});

GridDataTypes.dataType('boolean', {
    inputType: 'checkbox',
    format: function(data) {
        return data ? 'Yes' : 'No';
    },
    operators: ['==', '!='],
    defaultOperator: '==',
});

GridDataTypes.dataType('enum', {
    inputType: 'select',
    operators: [
        '==', '!='
    ],
    defaultOperator: '==',
});

GridDataTypes.dataType('entity', {
    inputType: 'select',
    operators: [
        '=='
    ],
    defaultOperator: '==',
});