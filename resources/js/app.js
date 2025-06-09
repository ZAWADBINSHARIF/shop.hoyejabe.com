import './bootstrap';

export default function productComponent(product, productSizes, productColors) {
    return {
        product: product,
        productQuantity: 1,
        selectedColor: null,
        extraColorPrice: 0,
        selectedSize: null,
        extraSizePrice: 0,
        productSizes: productSizes,
        productColors: productColors,
        showErrorMessage: {
            colorError: false,
            sizeError: false
        },
        selectedShippingArea: null,
        selectedShipingMethodCost: 0,
        get extraProductShippingCost() {
            return parseFloat(this.product.extra_shipping_cost);
        },
        get totalProductPrice() {
            return (
                (parseFloat(this.product.base_price) + this.extraColorPrice + this.extraSizePrice) *
                this.productQuantity
            );
        },
        get totalCost() {
            return parseFloat(this.totalProductPrice + this.extraProductShippingCost + this.selectedShipingMethodCost);
        },
        validateSelection() {
            let isValid = true;

            if (this.productColors.length > 0 && !this.selectedColor) {
                this.showErrorMessage.colorError = true;
                isValid = false;
            } else {
                this.showErrorMessage.colorError = false;
            }

            if (this.productSizes.length > 0 && !this.selectedSize) {
                this.showErrorMessage.sizeError = true;
                isValid = false;
            } else {
                this.showErrorMessage.sizeError = false;
            }

            return isValid;
        },
        increaseQuantity() {
            this.productQuantity++;
        },
        decreaseQuantity() {
            if (this.productQuantity > 1) {
                this.productQuantity--;
            }
        },
    };
}

Alpine.data('productComponent', productComponent);

document.addEventListener('alpine:init', () => {
    Alpine.store('cartSlider', {
        slideOverOpen: false
    });
    Alpine.store('categorySlider', {
        slideOverOpen: false
    });
});
