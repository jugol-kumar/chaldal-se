<template>
    <div>{{new Date(props.start_date) > new Date() ?  "Starting in" : "Ending in" }}:
        <span class="time_span">{{ days }}</span> :
        <span class="time_span">{{ hours }}</span> :
        <span class="time_span">{{ minuts }}</span> :
        <span class="time_span">{{ seconds }}</span>
    </div>
</template>

<script setup>

const props = defineProps({
    start_date:String,
    end_date:String,
})

import {ref} from "vue";

const nowDate = new Date();
const strtingForm = new Date(props.start_date)
const endingTo = new Date(props.end_date)
const isShowDills = ref(true)
const setTime = ref(new Date(props.start_date) > new Date() ? props.start_date : props.end_date);
const days = ref(0)
const hours = ref(0)
const minuts = ref(0)
const seconds = ref(0)

const clock = () =>{
    const endTimeStamp = new Date(setTime.value);
    const nowTimeStamp = new Date();
    const diffTimeStamp = (endTimeStamp - nowTimeStamp) / 1000;
    if(diffTimeStamp < 0) {
        clearInterval(interval)
        isShowDills.value = false;
        return;
    }

    // cal diff days
    const daysValue  = Math.floor(diffTimeStamp / 3600 / 24)
    days.value = daysValue < 10 ? "0"+daysValue : daysValue;
    // cal diff hour
    const hoursValue = Math.floor((diffTimeStamp / 3600) % 24)
    hours.value = hoursValue < 10 ? "0"+hoursValue : hoursValue;
    // min diff cal
    const minutsValue = Math.floor((diffTimeStamp / 60) % 60)
    minuts.value = minutsValue < 10 ? "0"+minutsValue : minutsValue;
    // sec diff cal
    const secondVal = Math.floor(diffTimeStamp % 60);
    seconds.value = secondVal < 10 ? "0"+secondVal : secondVal;
}


const interval = setInterval(() => {
    if(isShowDills.value){
        clock()
    }
}, 1000);
</script>


<style scoped>

.time_span{
    background: var(--bs-primary);
    padding: 15px 25px;
    color: white;
    font-weight: bold;
    font-size: 20px;
    border-radius: 5px;
}
</style>
