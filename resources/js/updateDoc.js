document.addEventListener('DOMContentLoaded', function () {
    var editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var docType = button.getAttribute('data-type');
            var docName = button.getAttribute('data-name');
            var typeId = button.getAttribute('data-type-id');

            // Populate the modal fields
            document.getElementById('update_type').value = docType;
            document.getElementById('update_name').value = docName;
            document.getElementById('type_id').value = typeId;

            // Set the action attribute of the form to the correct route
            var updateForm = document.getElementById('updateForm');
            updateForm.action = '{{ route("docType.update", ":type_id") }}'.replace(':type_id', typeId);
        });
    });
});
