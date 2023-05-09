<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
</script>

<template>
    <Head title="Weather" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Weather
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="search-box">
                        <input
                            type="text"
                            class="search-bar"
                            placeholder="Enter city name..."
                            v-model="query"
                            @keypress="fetchWeather"
                        />
                    </div>
                    <div class="weather-wrap" v-if="typeof weather.main != 'undefined'">
                        <div class="location-box" v-if="!error">
                            <div class="location">{{ weather.name }}, {{ weather.sys.country }}</div>
                            <div class="date">{{ dateBuilder() }}</div>
                        </div>

                        <div class="weather-box">
                            <div class="sunrise_sunset">
                                <div class="sunrise">Sunrise {{ weather.sunriseTime }} <img src="src/assets/svg/sunrise.svg" alt=""></div>
                                <div class="sunset">Sunset {{ weather.sunsetTime }} <img src="src/assets/svg/sunset.svg" alt=""></div>
                            </div>
                            <div class="temp">{{ Math.round(weather.main.temp) }}°C</div>
                            <div class="desc"> {{ weather.main.description }} </div>
                            <div class="weather_desc">It feels like {{ Math.round(weather.main.feels_like) }}°C</div>
                            <div class="upDown">
                                <div class="temp_min">The lowest temperature of the day {{ Math.round(weather.main.temp_min) }}°C</div>
                                <div class="temp_max">Highest temperature of the day {{ Math.round(weather.main.temp_max) }}°C</div>
                            </div>
                            <div class="pressure">Air pressure {{ weather.main.pressure }} hPa </div>
                            <div class="humidity">Air humidity {{ weather.main.humidity }}%</div>
                            <div class="wind_speed">Wind speed {{ Math.round(weather.wind.speed) }} m/s</div>
                            <div class="wind_deg">Direction of the wind {{ weather.wind.deg }}°</div>
                            <div class="weather">{{ weather.weather[0].main }} </div>
                        </div>
                    </div>
                    <div v-if="error">
                        <p class="text-center mb-5">{{this.errorMessage}}</p>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>


<script>
export default {
  name: 'app',
  data(){
    return{
      api_key: '3064d2d86300bda996db1b72d0a72a64',
      url_base: 'https://api.openweathermap.org/data/2.5/',
      query: '',
      error: false,
      errorMessage: '',
      weather: {}
    }
  },
  methods: {
    fetchWeather(e) {
      if (e.key == "Enter") {
        fetch(`${this.url_base}weather?q=${this.query}&units=metric&APPID=${this.api_key}`)
            .then(res => res.json())
            .then(data => {

                if (data.cod == '404') {
                    this.error = true;
                    this.errorMessage = data.message;
                }else{
                    this.error = false;

                    this.weather = data;

                    const sunriseUnix = data.sys.sunrise;
                    const sunsetUnix = data.sys.sunset;

                    const sunriseDate = new Date(sunriseUnix * 1000);
                    const sunsetDate = new Date(sunsetUnix * 1000);

                    const timezoneOffset = new Date().getTimezoneOffset() * 60 * 1000;
                    const sunriseTime = new Date(sunriseDate.getTime() + timezoneOffset);
                    const sunsetTime = new Date(sunsetDate.getTime() + timezoneOffset);

                    const sunriseHours = sunriseTime.getHours();
                    const sunriseMinutes = sunriseTime.getMinutes();

                    const sunsetHours = sunsetTime.getHours();
                    const sunsetMinutes = sunsetTime.getMinutes();

                    this.weather.sunriseTime = `${sunriseHours}:${sunriseMinutes}`;
                    this.weather.sunsetTime = `${sunsetHours}:${sunsetMinutes}`;
                }
            })
            .catch(error => {
              // this.errorMessage = error;
              console.error("There was an error!");
            });
      }
    },
    setResults (results){
      this.weather = results;
    },
    dateBuilder(){
      let d = new Date();
      let months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November",
      "December"];
      let days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

      let day = days[d.getDay()];
      let date = d.getDate();
      let month = months[d.getMonth()];
      let year = d.getFullYear();

      return `${day} ${date} ${month} ${year}`;
    }
  }
}
</script>

<style>
*{
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}
body{
  font-family: 'montserrat', sans-serif;
}

#app{
/*  background-image: url("./assets/Moonlit Asteroid.jpg");*/
  background-size: cover;
  background-position: bottom;
  transition: 0.4s;
}

main{
  min-height: 100vh;
  padding: 25px;

  background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.75));
}

.logo{
  color: #fff;
  font-family: "Kelly Slab";
  text-align: center;
  font-size: 50px;
}

.footer{
  bottom: 0;
  position: fixed;
  left: 7%;
  width: 85%;
  border-radius: 25px 25px 0px 0px;
  height: 9.5rem;
  background: rgba(225, 225, 225, 0.25);
  color: #fff;
  font-size: 25px;
  text-align: center;
}

.search-box{
  width: 100%;
  margin-bottom: 30px;
}

.search-box .search-bar{
  display: block;
  width: 100%;
  padding: 15px;

  color: #313131;
  font-size: 20px;

  appearance: none;
  border: none;
  outline: none;
  background: none;

  box-shadow: 0px 0px 8px rgba(0,0,0,0.25);
  background-color: rgba(255,255,255,0.5);
  border-radius: 16px;
  transition: 0.4s;
}

.search-box .search-bar:focus{
  box-shadow: 0px 0px 8px rgba(0,0,0,0.25);
  background-color: rgba(255,255,255,0.75);
}

.weather-box{
  left: 15%;
  display: flex;
  flex-direction: column;
  text-align: center;
    background-color: #3399ff;
  border-radius: 16px;
  width: 50rem;
  margin:auto;
  margin-bottom: 100px;
}

.upDown{
  display: flex;
  justify-content: space-between;
  padding: 35px;
}

.sunrise_sunset{
  display: flex;
  justify-content: space-between;
  padding: 20px 20px 0px 20px;
  color: #fff;
  font-size: 20px;
}

.location-box .location{
  color: #000;
  font-size: 32px;
  font-weight: 500;
  text-align: center;
  text-shadow: 1px 3px rgba(0,0,0,0.25);
}

.location-box .date{
  color: #fff;
  font-size: 20px;
  font-weight: 300;
  font-style: italic;
  text-align: center;
}

.weather-box .temp{
  display: inline;
  color: #fff;
  font-size: 102px;
  font-weight: 900;
  text-shadow: 3px 6px rgba(0,0,0,0.25);
  border-radius: 16px;
}

.weather-box .weather{
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  font-style: italic;
  text-shadow: 3px 6px rgba(0,0,0,0.25);
}

.weather_desc{
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  font-style: italic;
}
.temp_min{
  text-align: left;
  color: #fff;
  font-size: 20px;
  font-weight: 00;
  font-style: italic;
}
.temp_max{
  text-align: right;
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  font-style: italic;
}
.pressure{
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  font-style: italic;
}
.humidity{
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  font-style: italic;
}
.wind_speed{
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  font-style: italic;
}
.wind_deg{
  color: #fff;
  font-size: 20px;
  font-weight: 500;
  font-style: italic;
}
</style>
