Stripe.setPublishableKey('pk_test_51PSgUSGS5hz4ZPJTx4SCL283UTRrzE5omfnSFCBYQ7CU6Jef48NWNR1EquvzZEFEa8IIVSEQup1fVmzrgwoIPGkZ00m6OvExlQ');

function stripePay(event) {
    event.preventDefault();
    console.log("stripePay called");
    if (validateForm()) {
        $('#payNow').attr('disabled', 'disabled');
        $('#payNow').val('Payment Processing....');
        Stripe.card.createToken({
            number: $('#cardNumber').val(),
            cvc: $('#cardCVC').val(),
            exp_month: $('#cardExpMonth').val(),
            exp_year: $('#cardExpYear').val()
        }, stripeResponseHandler);
        return false;
    }
}

function stripeResponseHandler(status, response) {
    console.log("stripeResponseHandler called", status, response);
    if (response.error) {
        $('#payNow').attr('disabled', false);
        $('#message').html(response.error.message).show();
    } else {
        var stripeToken = response.id;
        $('#paymentForm').append("<input type='hidden' name='stripeToken' value='" + stripeToken + "' />");
        $('#paymentForm').submit();
    }
}

function validateForm() {
    var validCard = false;
    var valid = true;
    var cardCVC = $('#cardCVC').val();
    var cardExpMonth = $('#cardExpMonth').val();
    var cardExpYear = $('#cardExpYear').val();
    var cardNumber = $('#cardNumber').val();
    var emailAddress = $('#emailAddress').val();
    var customerName = $('#customerName').val();
    var customerAddress = $('#customerAddress').val();
    var customerCountry = $('#customerCountry').val();
    var validateName = /^[a-z ,.'-]+$/i;
    var validateEmail = /^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/;
    var validateMonth = /^(0[1-9]|1[0-2])$/;
    var validateYear = /^(20[2-9][0-9]|20[3-9][0-9])$/;
    var cvvExpression = /^[0-9]{3,4}$/;

    $('#cardNumber').validateCreditCard(function(result){
        if(result.valid) {
            $('#cardNumber').removeClass('require');
            $('#errorCardNumber').text('');
            validCard = true;
        } else {
            $('#cardNumber').addClass('require');
            $('#errorCardNumber').text('Invalid Card Number');
            validCard = false;
        }
    });

    if(!validCard) {
        valid = false;
    }

    if(!validateMonth.test(cardExpMonth)) {
        $('#cardExpMonth').addClass('require');
        $('#errorCardExpMonth').text('Invalid Month');
        valid = false;
    } else {
        $('#cardExpMonth').removeClass('require');
        $('#errorCardExpMonth').text('');
    }

    if(!validateYear.test(cardExpYear)) {
        $('#cardExpYear').addClass('require');
        $('#errorCardExpYear').text('Invalid Year');
        valid = false;
    } else {
        $('#cardExpYear').removeClass('require');
        $('#errorCardExpYear').text('');
    }

    if(!cvvExpression.test(cardCVC)) {
        $('#cardCVC').addClass('require');
        $('#errorCardCvc').text('Invalid CVC');
        valid = false;
    } else {
        $('#cardCVC').removeClass('require');
        $('#errorCardCvc').text('');
    }

    if(!validateName.test(customerName)) {
        $('#customerName').addClass('require');
        $('#errorCustomerName').text('Invalid Name');
        valid = false;
    } else {
        $('#customerName').removeClass('require');
        $('#errorCustomerName').text('');
    }

    if(!validateEmail.test(emailAddress)) {
        $('#emailAddress').addClass('require');
        $('#errorEmailAddress').text('Invalid Email Address');
        valid = false;
    } else {
        $('#emailAddress').removeClass('require');
        $('#errorEmailAddress').text('');
    }

    if(customerAddress === '') {
        $('#customerAddress').addClass('require');
        $('#errorCustomerAddress').text('Enter Address Detail');
        valid = false;
    } else {
        $('#customerAddress').removeClass('require');
        $('#errorCustomerAddress').text('');
    }

    if(customerCountry === '') {
        $('#customerCountry').addClass('require');
        $('#errorCustomerCountry').text('Enter Country Detail');
        valid = false;
    } else {
        $('#customerCountry').removeClass('require');
        $('#errorCustomerCountry').text('');
    }

    return valid;
}

function validateNumber(event) {
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode != 32 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
