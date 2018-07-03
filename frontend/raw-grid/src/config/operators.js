import { GridOperators } from 'raw-grid/extensions';

function registerOperators()
{
    var operators = {
        '==': {
            label: 'Equals',
            args: 1,
        },
        '!=': {
            label: 'Not equals',
            args: 1,
        },
        '>=': {
            label: 'Greater than or equals',
            args: 1,
        },
        '>': {
            label: 'Greater than',
            args: 1,
        },
        '<=': {
            label: 'Smaller than or equals',
            args: 1,
        },
        '<': {
            label: 'Smaller than',
            args: 1,
        },
        'CONTAINS': {
            label: 'Contains',
            args: 1,
        },
        'BETWEEN': {
            label: 'Between',
            args: 2,
        },
    };
    for(var k in operators) {
        GridOperators.operator(k, operators[k]);
    }
}
registerOperators();