<!-- login js-->
<!-- Plugin used-->
<script>
    let base_url = '<?= url('/') ?>';
</script>
<script src="{{ asset('admin/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset('admin/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('admin/datatables/datatables.min.js') }}"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.0/classic/ckeditor.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>


<script src="{{ asset('admin/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('admin/js/productDetail.js') }}"></script>
<script src="{{ asset('admin/js/custom.js') }}"></script>
<script src="{{ asset('admin/js/tooltip.js') }}"></script>

@if(isset($enable_listing_script) && $enable_listing_script==true)
<script>
    $(document).ready(function() {
        $(document).on('change', '.listing_featured input', function(e) {
            var isChecked = $(this).is(':checked'); // Correctly fetch the checkbox state
            var listingId = $(this).data('id'); // Correctly fetch the data-id
    
            console.log(isChecked, listingId); // Debugging info
    
            $.ajax({
                url: '{{ url("admin/listings/mark_as_featured") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}', // Include CSRF token for security
                    id: listingId,
                    featured: isChecked ? 1 : 0
                },
                success: function(response) {
                    console.log(response); // Debugging info
                    // if (response.status) {
                    //     // Handle success scenario
                    // } else {
                    //     alert('Error: ' + response.message);
                    // }
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again later.');
                }
            });
        });
    });
    </script>
    
@endif

<script type="text/javascript">
    $(document).ready(function() {
        // Function to add a new row
        $('.addNewRowTbl').click(function() {
            var newRow = `
            <tr>
                <td>
                    <input type="text" name="medication[]" class="form-control" placeholder="Medication" />
                </td>
                <td>
                    <input type="text" name="dosage[]" class="form-control" placeholder="Dosage" />
                </td>
                <td>
                    <textarea class="form-control" name="instructions[]" placeholder="Write some instructions for patient" rows="3"></textarea>
                </td>
                <td>
                    <a class="removeRow fs-6" href="javascript:void(0)">
                        <iconify-icon icon="ic:round-minus"></iconify-icon>
                    </a>
                </td>
            </tr>`;
            $('#rowRepeater tbody').append(newRow);
        });

        // Function to remove a row
        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });
    });
</script>
