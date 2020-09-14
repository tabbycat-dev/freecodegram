<template>
    <div v-if="warningZone">Are you still with us</div>
</template>

<script>
export default {
    name: "AutoLogout",

    data: function(){
        return{
            events: ['click', 'mousemove','mousedown','scroll', 'keypress','load'],
            warningTimer: null,
            logoutTimer: null,
            warningZone : false,
        }
    },
    mounted(){
        this.events.forEach(function(event){
            window.addEventListener(event, this.resetTimer);
        },this);
        this.setTimers();
    },
    destroyed(){
        this.events.forEach(function(event){
            window.removeEventListener(event, this.resetTimer);
        },this);
        this.resetTimer();
    },
    methods:{
        setTimers: function(){
            this.warningTimer = setTimeout(this.warningMessage, 4*1000); //14 mins 14 * 60 * 1000
            this.logoutTimer = setTimeout(this.logoutUser, 6*1000); //15 mins
            this.warningZone = false;
        },
        warningMessage: function(){
            this.warningZone = true;
        },
        logoutUser: function(){
            document.getElementById('logout-form').submit();
        },
        resetTimer: function(){
            clearTimeout(this.warningTimer);
            clearTimeout(this.logoutTimer);

            this.setTimers()

        }
    }

}



</script>

<style scoped>

</style>