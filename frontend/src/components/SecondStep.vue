<template>
  <section>
    <form>
      <ValidationObserver ref="observer" v-slot="{ passes }">
        <label class="label">Wybierz 10 dowolnych znaków (mogą się powtarzać)</label>
        <div class="letters-container">
          <a @click="buildChoiceLetterString(letter)" class="button button-letter" v-for="letter in shuffledLettersChoice">{{ letter }}</a>
        </div>

        <div class="columns">
          <div class="column is-four-fifths">
            <ValidationProvider rules="required|length:10" v-slot="{ errors, valid }">
              <b-field
                label="Wybrany ciąg znaków"
                :type="{ 'is-danger': errors[0] }"
                :message="errors"
              >
                <b-input
                  placeholder="Wybrane znaki"
                  type="text"
                  readonly=""
                  v-model="chars"
                  maxlength="10"
                ></b-input>
              </b-field>
            </ValidationProvider>
          </div>
          <div class="column">
            <b-button @click="clearCharsString" type="is-danger" outlined>Wyczyść</b-button>
          </div>
        </div>


        <div class="columns">
          <div class="column is-four-fifths">
            <ValidationProvider :rules="{ required: true, regexUuid: /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[0-9a-f]{4}-[0-9a-f]{12}$/i }" v-slot="{ errors, valid }">
              <b-field
                label="UUID"
                :type="{ 'is-danger': errors[0] }"
                :message="errors"
              >
                <b-input
                  placeholder="UUID"
                  type="text"
                  v-model="uuid"
                ></b-input>
              </b-field>
            </ValidationProvider>
          </div>
          <div class="column">
            <b-button @click="generateUUID" type="is-success" outlined>Generuj</b-button>
          </div>
        </div>

        <div class="buttons-container">
          <b-button class="left" @click="prevStep">Poprzedni krok</b-button>
          <b-button class="right" type="is-primary" @click="passes(nextStep)">Następny krok</b-button>
        </div>
      </ValidationObserver>
    </form>
  </section>
</template>

<script>
  import { ValidationObserver, ValidationProvider } from "vee-validate";
  import { uuid } from 'vue-uuid';

  export default {
    components: {
      ValidationObserver,
      ValidationProvider
    },
    props: {
      firstStepData: {
        type: Object
      }
    },
    watch: {
      firstStepData() {
        if (!this.firstStepData.province || !this.firstStepData.datesRange || !this.firstStepData.email){
          return;
        }

        const provinceChars = this.firstStepData.province.split('')
        const emailChars = this.firstStepData.email.replace(/[^a-zA-Ząćęłńóśźż]+/g, '').split('')
        const resultArray = provinceChars.concat(emailChars)

        let unique = [...new Set(resultArray)]
        this.shuffleArray(unique)

        this.shuffledLettersChoice = unique
        this.chars = ''
        this.uuid = ''
      }
    },
    methods: {
      nextStep() {
        this.$emit('submitData', {
          chars: this.chars,
          uuid: this.uuid
        });
      },
      generateUUID() {
        this.uuid = uuid.v4()
      },
      clearCharsString() {
        this.chars = ''
      },
      prevStep() {
        this.$emit('currentStep', 0)
      },
      buildChoiceLetterString(value) {
        if (this.chars.length === 10){
          this.$buefy.toast.open({
            message: 'Wybrano już 10 znaków, jeżeli chcesz je zmienić najpierw wyczyść aktualny zestaw.',
            position: 'is-bottom',
            queue: false
          });

          return;
        }

        this.chars = this.chars + value;
      },
      shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
          const j = Math.floor(Math.random() * (i + 1));
          [array[i], array[j]] = [array[j], array[i]];
        }

        return array;
      }
    },
    data() {
      return {
        shuffledLettersChoice: [],
        chars: '',
        uuid: ''
      }
    }
  }
</script>

<style>
  .column .button{ margin-top:42px; width: 100%; box-sizing: border-box; }
</style>
