<template>
  <section>
    <form>
      <ValidationObserver ref="observer" v-slot="{ passes }">

        <ValidationProvider rules="required" v-slot="{ errors, valid }">
          <b-field
            label="Wybierz województwo"
            :type="{ 'is-danger': errors[0] }"
            :message="errors"
          >
            <b-select
              expanded
              v-model="province"
            >
              <option v-for="province in provinces" v-bind:value="province.value">{{ province.text }}</option>
            </b-select>
          </b-field>
        </ValidationProvider>

        <ValidationProvider rules="required" v-slot="{ errors, valid }">
          <b-field
            label="Wybierz zakres dat"
            :type="{ 'is-danger': errors[0] }"
            :message="errors"
          >
            <b-datepicker
              required
              placeholder="Wybierz zakres..."
              :min-date="dateRange.minDate"
              :max-date="dateRange.maxDate"
              range
              v-model="datesRange"
            >
            </b-datepicker>
          </b-field>
        </ValidationProvider>

        <validation-provider :rules="{ required: true, regex: /^[a-zA-Z0-9ąćęłńóśźż_.+-]+@[a-zA-Z0-9-]+?\.com\.pl$/ }" v-slot="{ errors, valid }">
          <b-field
            label="E-mail"
            :type="{ 'is-danger': errors[0] }"
            :message="errors"
          >
            <b-input
              placeholder="Email"
              type="email"
              v-model="email"
            ></b-input>
          </b-field>
        </validation-provider>

        <div class="buttons-container">
          <b-button class="right" type="is-primary" @click="passes(nextStep)">Następny krok</b-button>
        </div>
      </ValidationObserver>
    </form>
  </section>
</template>

<script>
  import moment from 'moment'
  import "moment/locale/pl"
  import { ValidationObserver, ValidationProvider } from "vee-validate";

  moment.locale("pl");

  export default {
    components: {
      ValidationObserver,
      ValidationProvider
    },
    methods: {
      nextStep() {
        this.$emit('submitData', {
          province: this.province,
          datesRange: this.datesRange,
          email: this.email,
        });
      }
    },
    data() {
      return {
        province: '',
        datesRange: [],
        email: '',

        dateRange: {
          minDate: moment("01-01-1950", "DD-MM-YYYY").toDate(),
          maxDate: moment("31-12-2025", "DD-MM-YYYY").toDate(),
        },
        provinces: [
          {
              value: 'dolnoslaskie',
              text: 'Dolnośląskie'
          },
          {
              value: 'kujawskopomorskie',
              text: 'Kujawsko-pomorskie'
          },
          {
              value: 'lubelskie',
              text: 'Lubelskie'
          },
          {
              value: 'lubuskie',
              text: 'Lubuskie'
          },
          {
              value: 'lodzkie',
              text: 'Łódzkie'
          },
          {
              value: 'malopolskie',
              text: 'Małopolskie'
          },
          {
              value: 'opolskie',
              text: 'Opolskie'
          },
          {
              value: 'podkarpackie',
              text: 'Podkarpackie'
          },
          {
              value: 'podlaskie',
              text: 'Podlaskie'
          },
          {
              value: 'pomorskie',
              text: 'Pomorskie'
          },
          {
              value: 'slaskie',
              text: 'Śląskie'
          },
          {
              value: 'swietokrzyskie',
              text: 'Świętokrzyskie'
          },
          {
              value: 'warminskomazurskie',
              text: 'Warmińsko-mazurskie'
          },
          {
              value: 'wielkopolskie',
              text: 'Wielkopolskie'
          },
          {
              value: 'zachodniopomorskie',
              text: 'Zachodniopomorskie'
          }
        ]
      }
    },
  }
</script>
