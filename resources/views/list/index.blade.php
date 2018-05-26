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
                    Manage List
                </div>


                <div id="myel">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input v-model="new_list.name" class="form-control" placeholder="New List Name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-primary" @click="createNewList()">
                                Create New List
                            </button>
                        </div>
                    </div>

                    <div class="list-content m-b-md">
                        My List
                    </div>

                
                     <ul class="list-group" v-if ="items">
                     
                           <button class="btn btn-primary" @click="addSubcriber()">
                                <span class="glyphicon glyphicon-envelope"></span> Add Subcriber
                            </button>
                          
                        <li class="list-group-item" v-for="item in items">
                            <p class="text-dark">Name: @{{ item.name }}</p>
                           <p class="text-dark">ID: @{{ item.id }}</p>
                            <p class="text-dark">Member Count: @{{ item.stats.member_count }}</p>
                           <input v-model="email[item.id]" class="form-control" placeholder="Email" required>
                        </li>
                    </ul> 
                </div>
            </div>



<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://unpkg.com/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    <script>
    new Vue({
        el: '#myel',
        data: {
            get_lists_url : '<?= route('getAllLists') ?>',
            create_list_url : '<?= route('createList') ?>',
            add_member_url : '<?= route('add_member_url') ?>',
            items : null,
            new_list : {
                name : 'List Name',
                _token: '{{csrf_token()}}'
            },
            email: [],
            subcribers: {
                post_data: [],
                _token: '{{csrf_token()}}'
            }
        },

        mounted() {
            this.getAllList();
        },

    methods : {

        createNewList() {
            let self = this;
            axios.post(this.create_list_url, this.new_list)
              .then(function (response) {
                alert("Successfully created the New List");
                self.getAllList();
                console.log(response);
              })
              .catch(function (error) {
                alert("Fail to create New List");
                console.log(error);
              });

        },



        getAllList(){
            axios.get(this.get_lists_url).then(response => {
                this.items = response.data.lists;
            });

        },


        addSubcriber(){
 
        let self = this;
        let i = 0;
            $.each(this.items, function(key, value) {
            
            if (self.email[self.items[key].id]){
                self.subcribers.post_data[i] = {
                    id : self.items[key].id,
                    email : self.email[self.items[key].id]
                };
            }
            i++;
        });

        axios.post(this.add_member_url, self.subcribers, 
        {
        })
          .then(function (response) {
            console.log(response);
          })
          .catch(function (error) {
            console.log(error);
          });
          //location.reload();

        }
    },


    })

</script>









    </body>
</html>
