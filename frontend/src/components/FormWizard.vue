<template>
  <section class="center-section">
    <div class="content has-text-centered">
      <h1>Formularz generowania klucza</h1>
    </div>
    <b-steps v-model="activeStep" size="is-large" :has-navigation="false">
      <b-step-item :key="1" step="1" label="Podstawowe" :clickable="false">
        <FirstStep v-on:submitData="onFirstStepSubmit" :firstStepData="this.data.firstStep" />
      </b-step-item>
      <b-step-item :key="2" step="2" label="Dodatkowe" :clickable="false">
        <SecondStep v-on:submitData="onSecondStepSubmit" v-on:currentStep="onChangeCurrentStep" :firstStepData="this.data.firstStep" />
      </b-step-item>
      <b-step-item :key="3" step="3" label="Podsumowanie" :clickable="false">
        <ThirdStep v-on:currentStep="onChangeCurrentStep" :formsData="this.formsData" />
      </b-step-item>
    </b-steps>
  </section>
</template>

<script>
  import FirstStep from "./FirstStep";
  import SecondStep from "./SecondStep";
  import ThirdStep from "./ThirdStep";

  export default {
    methods: {
      onFirstStepSubmit (value) {
        this.data.firstStep = value
        this.activeStep += 1
        this.setFormsData()
      },
      onSecondStepSubmit (value) {
        this.data.secondStep = value
        this.activeStep += 1
        this.setFormsData()
      },
      onChangeCurrentStep (step) {
        this.activeStep = step
      },
      setFormsData() {
        const firstStepData = this.data.firstStep
        const SecondStepData = this.data.secondStep

        this.formsData = {...firstStepData, ...SecondStepData}
      }
    },
    data() {
      console.log(process.env.NODE_ENV);
      console.log(process.env.VUE_APP_API_HOST);
      return {
        activeStep: 0,
        formsData: {},
        data: {
          firstStep: {},
          secondStep: {}
        }
      }
    },
    components: {
      FirstStep, SecondStep, ThirdStep
    }
  }
</script>
