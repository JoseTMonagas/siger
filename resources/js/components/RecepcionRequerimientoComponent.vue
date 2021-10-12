<template>
  <v-stepper v-model="currentStep" non-linear>
    <v-stepper-header>
      <v-stepper-step
        v-for="(guiaDespacho, index) in guiasDespacho"
        :key="guiaDespacho.id"
        editable
        :step="index"
      >
        <strong>
          {{ guiaDespacho.fecha }}
          - {{ guiaDespacho.nombre_centro }} - {{ guiaDespacho.folio }}
        </strong>
      </v-stepper-step>
      <v-stepper-step :step="lastStep" editable>
        <strong>Recibir Pedido</strong>
      </v-stepper-step>
    </v-stepper-header>
    <v-stepper-items>
      <v-stepper-content
        v-for="(guiaDespacho, indexGuias) in guiasDespacho"
        :key="guiaDespacho.febos_id"
        :step="indexGuias"
      >
        <div class="container-fluid">
          <div class="row">
            <div class="col-md">
              <v-simple-table fixed-header height="700">
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th>Rechazar</th>
                      <th>Detalle</th>
                      <th>Cant Solicitada</th>
                      <th>Cant Despachado</th>
                      <th>Observacion</th>
                      <th>Motivo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      v-for="(producto,
                      indexProductos) in guiaDespacho.productos"
                      :key="indexProductos"
                      :class="getContextClass(indexGuias, indexProductos)"
                    >
                      <td>
                        <v-checkbox
                          v-model="
                            productosEditados[indexGuias][indexProductos]
                              .rechazado
                          "
                        ></v-checkbox>
                      </td>
                      <td>
                        {{ producto.detalle }}
                      </td>
                      <td>
                        {{ producto.pivot.cantidad }}
                      </td>
                      <td>
                        {{ producto.pivot.real }}
                      </td>
                      <td>
                        {{ producto.pivot.observacion }}
                      </td>
                      <td>
                        <small
                          v-if="
                            productosEditados[indexGuias][indexProductos]
                              .rechazado
                          "
                          class="text-secondary"
                        >
                          <b>
                            Ingrese motivo del rechazo
                          </b>
                        </small>
                        <v-text-field
                          class="my-2"
                          outlined
                          label="Motivo del Rechazo"
                          :disabled="
                            !productosEditados[indexGuias][indexProductos]
                              .rechazado
                          "
                          v-model="
                            productosEditados[indexGuias][indexProductos].motivo
                          "
                        ></v-text-field>
                      </td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </div>
          </div>
          <div class="row justify-content-around">
            <div class="col-md-2">
              <v-btn color="primary" @click="currentStep = ++indexGuias"
                >Continuar</v-btn
              >
            </div>
          </div>
        </div>
      </v-stepper-content>
      <v-stepper-content :step="lastStep">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md">
              <strong>
                Los siguientes productos seran rechazados:
              </strong>
            </div>
          </div>
          <div class="row">
            <div class="col-md">
              <v-simple-table fixed-header length="700">
                <template v-slot:default>
                  <thead>
                    <tr>
                      <th>Folio Guia</th>
                      <th>Detalle</th>
                      <th>Motivo</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(producto, index) in rechazados">
                      <td>{{ getFolioByProductId(producto.id) }}</td>
                      <td>{{ producto.detalle }}</td>
                      <td>{{ producto.motivo }}</td>
                    </tr>
                  </tbody>
                </template>
              </v-simple-table>
            </div>
          </div>
          <div class="row justify-content-around">
            <div class="col-md-2">
              <v-btn @click="save" color="success">Aceptar y Finalizar</v-btn>
            </div>
          </div>
        </div>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>
<script>
export default {
  props: {
    guiasDespacho: {
      type: Array,
      required: true
    },
    storeRoute: {
      type: String,
      required: true
    }
  },
  mounted() {
    for (let i = 0; i < this.guiasDespacho.length; i++) {
      var lista = this.guiasDespacho[i].productos;
      this.productosEditados.push(lista);
    }
  },
  computed: {
    lastStep() {
      return this.guiasDespacho.length;
    },
    rechazados() {
      const productos = this.productosEditados.flat();
      return productos.filter(producto => producto.rechazado);
    }
  },
  data() {
    return {
      currentStep: 0,
      productosEditados: []
    };
  },
  methods: {
    getContextClass(indexGuias, indexProductos) {
      const rechazado = this.productosEditados[indexGuias][indexProductos]
        .rechazado;

      if (rechazado) {
        return "table-warning";
      } else {
        return "";
      }
    },
    validateStep(indexGuia) {
      if (this.productosEditados.length > 0 && indexGuia > -1) {
        return this.productosEditados[indexGuia].some(
          producto => producto.rechazado && producto.motivo == ""
        );
      }
      return true;
    },
    getFolioByProductId(productId) {
      const guia = this.guiasDespacho.find(guia =>
        guia.productos.some(producto => producto.id == productId)
      );

      if (guia != undefined) {
        return guia.folio;
      }
      return "";
    },
    getGuiaIdByProductId(productId) {
      const guia = this.guiasDespacho.find(guia =>
        guia.productos.some(producto => producto.id == productId)
      );

      if (guia != undefined) {
        return guia.id;
      }
      return false;
    },
    save() {
      let rechazados = this.rechazados;
      rechazados.forEach(rechazado => {
        rechazado.guia = this.getGuiaIdByProductId(rechazado.id);
      });
      axios
        .post(this.storeRoute, { rechazados })
        .catch(function(error) {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log("Error", error.message);
          }
          console.log(error.config);
        })
        .then(resp => {
          if (resp.status == 201) {
            alert("Guardado exitosamente");
            window.location.href = resp.data;
          }
        });
    }
  }
};
</script>
