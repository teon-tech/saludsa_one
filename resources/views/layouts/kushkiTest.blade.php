<html>
    <head>
        <title>Checkout</title>
        <script src="https://cdn.kushkipagos.com/kushki-checkout.js"></script>
        <script src="https://cdn.kushkipagos.com/kushki.min.js"></script>
      </head>
<body>
    <form id="payment-form" action={{route('KushkiConfirmar')}} method="post">
        @csrf
        <input type="hidden" name="cart_id" value="123">
    </form>
</body>
<script type="text/javascript">
    var kushki = new KushkiCheckout({
        form: "payment-form",
        merchant_id: "0be3fbcaf3dc49319d0999e7bcd70c62", // Reemplaza esto por tu Public Key
        amount: "14.99",
        currency: "USD",
        payment_methods:["credit-card"],
        is_subscription: true,
        inTestEnvironment: true // Configurado en modo prueba
    });

    var kushkiTest = new Kushki({
  merchantId: '0be3fbcaf3dc49319d0999e7bcd70c62', 
  inTestEnvironment: true,
  regional:false
});
kushkiTest.requestTokenCharge({
  subscriptionId: "1632137966604000" // Replace with your subscription id
}, (response) => {
  if(response.code){
    console.log(response);
    // Submit your code to your back-end
  } else {
    console.error('Error: ',response.error, 'Code: ', response.code, 'Message: ',response.message);
  }
});
</script>
</html>


