# Dual Formwidget

Two form fields / form widget for one field, twice the fun.

### example yaml config

    donation:
        label: Donation
        jsonable: false
        type: Keios\DualFormWidget\FormWidgets\DualFormWidget
        left:
            label: Please enter how much you want to donate
            type: text
        right:
            label: or select a predefined amount
            type: dropdown

This results in:
 * http://i.keios.eu/snapshot-2015-09-29-12-27-30.png
 * http://i.keios.eu/snapshot-2015-09-29-12-28-08.png

Attribute "jsonable" saves data as an array of form (so the field should be jsonable!):

    [
        'side' => 'left' | 'right',
        'value' => result
    ],

where 'side' field is meta field belonging to formwidget and allowing to recognize which nested formwidget
should be used and 'value' is value obtained from user. Therefore when jsonable: true you will have to refer
to your data using:

    $model->field['value']

or even better:

    array_get($model->field, 'value', 'some default')

Clearly, this is most useful in models implementing SettingsModel behavior since all fields are serialized to json.

Attributes left and right are normal field definitions.