export default {
    updateAuxProperties(auxiliar, data) {
        auxiliar.id = data.id;
        auxiliar.name = data.name;
        auxiliar.role = data.role;
        auxiliar.phone = data.phone;
        auxiliar.active = data.active;
    },
    updateAuxProduct(productAux, data) {
        productAux.id = data.id;
        productAux.name = data.name;
        productAux.unit = data.unit;
        productAux.description = data.description;
        productAux.dimension = data.dimension;
        productAux.active = data.active == 1;
        productAux.image = data.image;
        productAux.id_family = data.id_family;
    },
    deleteAuxProduct(productAux) {
        productAux.id = null;
        productAux.name = null;
        productAux.unit = null;
        productAux.description = null;
        productAux.dimension = null;
        productAux.active = null;
        productAux.image = null;
        productAux.id_family = null;
    },
    deleteAuxProperties(auxiliar) {
        auxiliar.id = null;
        auxiliar.name = null;
        auxiliar.role = null;
        auxiliar.phone = null;
        auxiliar.active = null;
    },
}