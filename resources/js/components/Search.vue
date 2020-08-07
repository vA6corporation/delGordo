<template>
<div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" style="display: block; padding-right: 15px;" aria-modal="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="SEARCH">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="tim-icons icon-simple-remove"></i>
        </button>
      </div>
    </div>
  </div>
</div>
</template>

<script>
export default {
  mounted() {
    setTimeout(function() {
      $('#searchModal').modal('show')
    }, 1000);
  }
}
</script>

<style>
/* #searchModal .modal-dialog {
  margin: 20px;
} */

/* .modal-search .modal-dialog {
  margin: 20px auto;
  max-width: 650px;
} */

.modal-search .modal-dialog input {
  border: none;
  font-size: 17px;
  font-weight: 100;
}

.modal-search .modal-dialog span {
  font-size: 35px;
  color: #b7b7b7;
}

.modal-search .modal-content .modal-header {
  padding: 24px;
}

.modal-search .modal-header .close {
  color: #555555;
  top: 30px !important;
}

.modal-search .modal-footer {
  border-top: 2px solid #f9f9f9;
  margin: 0px 25px 20px;
}

.modal-search .modal-dialog {
  max-width: 1000px;
  margin: 20px auto;
}

.modal-search .modal-dialog .form-control {
  border: none;
  color: #222a42;
}

.modal-search .modal-dialog .form-control::placeholder {
  color: #222a42;
}

.navbar .navbar-nav .modal-search .modal-dialog {
  padding: 0 40px;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050;
  display: none;
  width: 100%;
  height: 100%;
  overflow: hidden;
  outline: 0;
}

.modal-dialog {
  position: relative;
  width: auto;
  margin: 0.5rem;
  pointer-events: none;
}
</style>