<section id="carrito">
    <div class="container">
        <button class="btn btn-dark">
            Modal
        </button>
    </div>    
</section>

<script>
$('#myModal').on('shown.bs.modal', function () {
  $('#myInput').trigger('focus')
})
</script>