function AddToCart(productId)
{
    var cart = GetCart();

    var exists = cart.find(item => item.id === productId);

    if(exists) exists.quantity++;
    else cart.push({id: productId, quantity: 1});

    localStorage.setItem('cart', JSON.stringify(cart));

    UpdateCart()
}

function UpdateCart()
{
    let cart = GetCart();

    console.log(cart);

    let cartLength = cart.reduce(function(sum, item) {
        return sum + item.quantity;
    }, 0);

    let cartText = 'Koszyk (' + cartLength + ')';

    if(cartLength === 0) cartText = "Koszyk (PUSTY)";

    $('.cart-btn').text(cartText);

    $.ajax({
        type: 'POST',
        url: '/public/index.php/update-cart',
        contentType: 'application/json',
        data: JSON.stringify(cart),
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            console.error(error);
        }
    });
}

function GetCart()
{
    return JSON.parse(localStorage.getItem('cart')) || [];
}

function ClearCart()
{
    localStorage.removeItem('cart');
    UpdateCart();
}


$(document).ready(function ()
{
    $('.add-to-cart').click(function() {
        var productId = $(this).data('product-id');
        AddToCart(productId);
    });

    $('.cart-btn').click(function () {
        $.ajax({
            type: 'POST',
            url: '/checkout', // endpoint dla koszyka
            contentType: 'application/json',
            data: JSON.stringify({ cart: GetCart() }),
            success: function (response) {
                console.log(response);
            },
            error: function (error) {
                console.error(error);
            }
        });
    });

    UpdateCart()
});