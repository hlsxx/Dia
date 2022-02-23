<template>
  <div class="vega-clock">
    <span>{{ hours }}</span>
    <span>{{ minutes }}</span>
    <span>{{ seconds }}</span>
  </div>
</template>

<script>
export default {
  data() {
    return {
      hours: "00",
      minutes: "00",
      seconds: "00"
    }
  },
  methods: {
    setTime () {
      const date = new Date();
      this.hours = String(date.getHours()).padStart(2, '0');
      this.minutes = String(date.getMinutes()).padStart(2, '0');
      this.seconds = String(date.getSeconds()).padStart(2, '0');
    },
    setTimeRepeat() {
      setInterval( () => {
        this.setTime();
      });
    }
  },
  mounted () {
    this.setTime();
  },
  created() {
    this.setTimeRepeat();
  }
}
</script>