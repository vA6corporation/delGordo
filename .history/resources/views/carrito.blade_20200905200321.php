<section id="carrito">
    <div class="container">
    <h2>Modal Example</h2>
  <!-- Trigger the modal with a button -->
  <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
    </div>    
</section>
  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
            <img src="/assets/images/logo.png" style="height:7vh" alt="">  
            <h4 class="modal-title">Total a pagar : S/ 478.34</h4>
        </div>
        <div class="modal-body">
        <form>
            <div class="form-row">
                <div class="col-7">
                    <label for="card_name">Nombre</label>
                    <input id="card_name" type="text" class="form-control" placeholder="Del Gordo">
                </div>
                <div class="col-5">
                    <label for="card_number">Número</label>
                    <input id="card_number" type="text" class="form-control" placeholder="4557 8808 1234 5678">
                </div>
            </div>
            <br>
            <div class="form-row">
                <div class="col-4">
                    <label for="card_month">Mes</label>
                    <select id="card_month" class="form-control">
                        <option selected>01</option>
                        <option>02</option>
                    </select>
                </div>
                <div class="col-4">
                    <label for="card_year">Mes</label>
                    <select id="card_year" class="form-control">
                        <option selected>2021</option>
                        <option>2022</option>
                    </select>
                </div>
                <div class="col-4">
                    <label for="card_year">CVV</label>
                    <input id="card_year" type="text" class="form-control" placeholder="123">
                </div>
            </div>
            <br>
            <div class="form-row">
                <div class="col-6">
                    <label for="card_doctype">Tipo DOC</label>
                    <select id="card_doctype" class="form-control">
                        <option selected>DNI</option>
                        <option>RUC</option>
                    </select>
                </div>
                <div class="col-6">
                    <label for="card_docnumber">Número DOC</label>
                    <input id="card_docnumber" type="text" class="form-control" placeholder="01234567">
                </div>
            </div>
        </form>
        </div>
        <div class="modal-footer">  
            <div class="row">
                <div type="button" class="btn btn-dark" styke="color:white" data-dismiss="modal">Pagar</div>
            </div>
            <div class="row">
                <img src="/assets/images/pay.jpg" style="wid:">
            </div>    
    </div>
      </div>
      
    </div>
  </div>
  
<style>
    .creditCardForm {
    max-width: 700px;
    background-color: #fff;
    margin: 100px auto;
    overflow: hidden;
    padding: 25px;
    color: #4c4e56;
}
.creditCardForm label {
    width: 100%;
    margin-bottom: 10px;
}
.creditCardForm .heading h1 {
    text-align: center;
    font-family: 'Open Sans', sans-serif;
    color: #4c4e56;
}
.creditCardForm .payment {
    float: left;
    font-size: 18px;
    padding: 10px 25px;
    margin-top: 20px;
    position: relative;
}
.creditCardForm .payment .form-group {
    float: left;
    margin-bottom: 15px;
}
.creditCardForm .payment .form-control {
    line-height: 40px;
    height: auto;
    padding: 0 16px;
}
.creditCardForm .owner {
    width: 63%;
    margin-right: 10px;
}
.creditCardForm .CVV {
    width: 35%;
}
.creditCardForm #card-number-field {
    width: 100%;
}
.creditCardForm #expiration-date {
    width: 49%;
}
.creditCardForm #credit_cards {
    width: 50%;
    margin-top: 25px;
    text-align: right;
}
.creditCardForm #pay-now {
    width: 100%;
    margin-top: 25px;
}
.creditCardForm .payment .btn {
    width: 100%;
    margin-top: 3px;
    font-size: 24px;
    background-color: #2ec4a5;
    color: white;
}
.creditCardForm .payment select {
    padding: 10px;
    margin-right: 15px;
}
.transparent {
    opacity: 0.2;
}
@media(max-width: 650px) {
    .creditCardForm .owner,
    .creditCardForm .CVV,
    .creditCardForm #expiration-date,
    .creditCardForm #credit_cards {
        width: 100%;
    }
    .creditCardForm #credit_cards {
        text-align: left;
    }
}
</style>