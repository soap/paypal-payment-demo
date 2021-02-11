@extends('layouts.app')

@section('content')
    <div class="containner">
        <!-- Set up a container element for the button -->
        <div id="paypal-button-container"></div>
  
    </div>
     <!-- Include the PayPal JavaScript SDK -->
    <script src="{{ $paypal_js }}"></script>

    <script>
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({

            // Set up the transaction
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: '1.00'
                        }
                    }]
                });
            },

            // Finalize the transaction
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    // Show a success message to the buyer
                    alert('Transaction completed by ' + details.payer.name.given_name + '!');
                });
            }


        }).render('#paypal-button-container');
    </script>
@endsection