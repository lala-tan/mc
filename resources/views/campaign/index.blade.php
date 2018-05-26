<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #000;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 1000px;
                margin: 0;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .list-content {
                font-size: 30px;
            }

            .links > a {
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }


            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>

        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="links">
                    <a href="{{ route('list.index') }}">Manage List</a>
                    <a href="{{ route('campaign.index') }}">Manage Campaign</a>
                </div>
                <div class="title m-b-md">
                    Manage Campaign
                </div>



                <div id="myel">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <input v-model="new_campaign.name" class="form-control" placeholder="New Campaign Name" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <select v-model="new_campaign.selected_list_id">
                              <option v-for="option in options" v-bind:value="option.id">
                                @{{ option.name }}
                              </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <button class="btn btn-primary" @click="createNewCampaign()">
                                Create New Campaign
                            </button>
                        </div>
                    </div>

                    <div class="list-content m-b-md">
                        My Campaign
                    </div>

                
                     <ul class="list-group" v-if ="items">
                     
                           <button class="btn btn-primary" @click="addScheduler()">
                                <span class="glyphicon glyphicon-time"></span> Add Scheduler
                            </button>
                          
                        <li class="list-group-item" v-for="item in items">
                            <p class="text-dark">Name: @{{ item.settings.title }}</p>
                            <p class="text-dark">Name: @{{ item.recipients.list_name }}</p>
                            <p class="text-dark">ID: @{{ item.id }}</p>
                            <p class="text-dark">Type: @{{ item.type }}</p>
                            <p class="text-dark">Status: @{{ item.status }}</p>
                            <p class="text-dark" v-if="item.status == 'schedule'">Scheduled Time: @{{ item.send_time }}</p>
                            <input v-model="date_time[item.id]" v-if="item.status != 'schedule'" class="form-control" placeholder="Add Scheduler(eg: 2018-05-25T19:13:00+00:00)" required>
                        </li>
                    </ul> 
                </div>
            </div>



<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>


    var options = <?= json_encode($options) ?>;
    var selected = "<?= $selected_listID ?>";

    new Vue({
        el: '#myel',
        data: {
            get_campaigns_url : '<?= route('getAllCampaigns') ?>',
            create_campaign_url : '<?= route('createCampaign') ?>',
            add_scheduler_url : '<?= route('add_scheduler_url') ?>',
            items : null,
            new_campaign : {
                name : 'Campaign Name',
                selected_list_id: selected,
                _token: '{{csrf_token()}}'
            },
            date_time: [],
            schedulers: {
                post_data: [],
                _token: '{{csrf_token()}}'
            },
            
            options: options,

        },

        mounted() {
            this.getAllCampaigns();
        },

    methods : {

        createNewCampaign() {
            let self = this;
            axios.post(this.create_campaign_url, this.new_campaign)
              .then(function (response) {
                alert("Successfully created the New Campaign");
                self.getAllCampaigns();
                console.log(response);
              })
              .catch(function (error) {
                alert("Fail to create New Campaign");
                console.log(error);
              });

        },



        getAllCampaigns(){
            axios.get(this.get_campaigns_url).then(response => {
                this.items = response.data.campaigns;
            });

        },


        addScheduler(){
 
        let self = this;
        let i = 0;
            $.each(this.items, function(key, value) {
            if (self.date_time[self.items[key].id]){
                self.schedulers.post_data[i] = {
                    id : self.items[key].id,
                    date_time : self.date_time[self.items[key].id]
                };
            }
            i++;
        });
        //console.log(self.scheduler.post_data);

        axios.post(this.add_scheduler_url, self.schedulers, 
        {
        })
          .then(function (response) {
            console.log(response);
          })
          .catch(function (error) {
            console.log(error);
          });
          location.reload();

        }
    },


    })

</script>









    </body>
</html>
