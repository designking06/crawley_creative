(function ($, Drupal) {
    Drupal.behaviors.channels = {
        attach: function (context, settings) {

            $(document).ready(function () {
                $('#table-lineup', context).once('channels').each(function () {

                    if (jQuery().DataTable) {
                        Drupal.behaviors.channels.setDatatable();
                    } else {
                        setTimeout(function () {
                            Drupal.behaviors.channels.setDatatable();
                        }, 1000);
                    }

                });
            });
        },

        setDatatable: function () {
            let table = $('#table-lineup').DataTable({
                scrollY: '500px',
                scrollCollapse: true,
                paging: false,
                info: false,
                aoColumns: [
                    null,
                    null,
                    null,
                    {"orderSequence": [""]},
                    {"orderSequence": [""]},
                    {"orderSequence": [""]},
                    {"orderSequence": [""]}
                ]
            });

            // Package radio button on change.
            $('#edit-package input:radio').change(function () {
                let package = $(this).val();
                let column_id = '';

                switch(package) {
                    case 'all':
                        small.visible(true);
                        medium.visible(true);
                        large.visible(true);
                        break;
                }
                // Get the column API object
                let column = table.column(column_id);
                // Toggle the visibility
                column.visible(true);

            });
            // Chanel type radio button on change.
            $('#edit-channel-type input:radio').change(function () {
                let type = $(this).val();
                let all = table.column(1);
                let hd = table.column(2);
                switch(type) {
                    case 'all':
                        all.visible(true);
                        hd.visible(true);
                        wowtvType.visible(true);
                        break;

                    case 'hd':
                        all.visible(false);
                        hd.visible(true);
                        wowtvType.visible(false);
                        break;
                }

            });

        }
    };
})(jQuery, Drupal);
