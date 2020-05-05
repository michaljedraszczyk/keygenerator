<template>
  <section>
    <form v-if="!successResponse && !errorResponse">
      <ValidationObserver ref="observer" v-slot="{ passes }">
        <table class="table is-bordered">
          <tbody>
          <tr v-for="row in summary">
            <td><strong>{{ row.name }}</strong></td>
            <td>{{ row.value }}</td>
          </tr>
          </tbody>
        </table>

        <div class="buttons-container">
          <b-button class="left" @click="prevStep">Wróc do edycji</b-button>
          <b-button class="right" type="is-primary" @click="passes(onSubmit)">Zapisz dane</b-button>
        </div>
      </ValidationObserver>
    </form>
    <div class="success-info" v-if="successResponse">
      <b-icon pack="fas" icon="check" size="is-large" type="is-primary"></b-icon>
      <h1>Dziękujemy za wygenerowanie klucza!</h1>
      <a @click="startFromBegin" class="button is-text">Wygeneruj nowy klucz</a>
    </div>
    <div class="success-info" v-if="errorResponse">
      <b-icon pack="fas" icon="check" size="is-large" type="is-danger"></b-icon>
      <h1>Wystąpił nieoczekiwany błąd!</h1>
      <a @click="startFromBegin" class="button is-text">Wygeneruj nowy klucz</a>
    </div>
  </section>
</template>

<script>
  import moment from 'moment'
  import "moment/locale/pl"
  import {ValidationObserver, ValidationProvider} from "vee-validate";
  import axios from 'axios'

  moment.locale("pl");

  export default {
    components: {
      ValidationObserver,
      ValidationProvider
    },
    props: {
      formsData: {
        type: Object
      }
    },
    methods: {
      startFromBegin() {
        this.successResponse = false
        this.errorResponse = false
        this.$emit('currentStep', 0)
      },
      onSubmit() {
        axios.post(`http://api.tebrekrutacja.test/api/date-keys/period`, {
          from: this.from,
          to: this.to,
          keyTemplate: this.keyTemplate
        }).then(response => {
          if (response.status === 204) {
            this.successResponse = true;
            return;
          }

          this.errorResponse = true;
        });
      },
      prevStep() {
        this.$emit('currentStep', 1)
      },
      getSampleString(onlyTemplate = false) {
        if (!this.formsData.chars || !this.formsData.uuid) {
          return;
        }

        const uuidString = this.formsData.uuid.replace(/[^a-zA-Z0-9]/g, "");
        const uuidArray = [...uuidString]
        const choicesLettersArray = [...this.formsData.chars, ...this.formsData.chars, ...this.formsData.chars, ...this.formsData.chars].slice(0, 32)
        let resultArray = [];

        for (let i = 0; i < uuidArray.length; i++) {
          resultArray.push(choicesLettersArray[i])
          resultArray.push(uuidArray[i])
        }

        let zippedString = resultArray.join("").replace()

        const findFor = ['ą', 'ć', 'ę', 'ł', 'ń', 'ó', 'ś', 'ź', 'ż']
        const replaceWith = ['a', 'c', 'e', 'l', 'n', 'o', 's', 'x', 'z']

        findFor.forEach((tag, i) => zippedString = zippedString.replace(new RegExp(tag, "g"), replaceWith[i]))

        const month = moment(this.formsData.datesRange[0]).format('MM')
        const day = moment(this.formsData.datesRange[0]).format('DD')
        const year = moment(this.formsData.datesRange[0]).format('YYYY')

        const prefix = month + year[0] + year[1]
        const suffix = day + year[2] + year[3]

        return (onlyTemplate) ? zippedString : prefix + zippedString + suffix
      }
    },
    watch: {
      formsData() {
        this.from = moment(this.formsData.datesRange[0]).format('YYYY-MM-DD')
        this.to = moment(this.formsData.datesRange[1]).format('YYYY-MM-DD')

        this.keyTemplate = this.getSampleString(true);

        this.summary = [
          {
            name: 'Wybrany zakres dat',
            value: this.from + ' - ' + this.to
          },
          {
            name: 'Wybrane litery',
            value: this.formsData.chars
          },
          {
            name: 'UUID',
            value: this.formsData.uuid
          },
          {
            name: 'Klucz',
            value: this.getSampleString(false)
          },
        ]
      }
    },
    data() {
      return {
        summary: [],
        successResponse: false,
        errorResponse: false,
        from: null,
        to: null,
        keyTemplate: ''
      }
    }
  }
</script>
