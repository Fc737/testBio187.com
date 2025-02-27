<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Profile</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <div class="row my-3">
                <h1>Profile</h1>
            </div>

            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-auto">
                    <form action="" method="POST" >
                        
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="f_name">First name</label>
                                <input class="form-control" type="text" id="f_name" name="f_name" required maxlength="25"/>
                            </div>
                            <div class="col-1">
                                <label class="form-label" for="middle">M</label>
                                <input class="form-control" type="text" name="middle" maxlength="2"/>
                            </div>
                            <div class="col">
                                <label class="form-label" for="l_name">Last name</label>
                                <input class="form-control" type="text" name="l_name" required maxlength="25"/>
                            </div>
                        </div>
                        
                        <div class="row mb-3 g-3">
                            <div class="col-12">
                                <label class="form-label" for="street">Street </label>
                                <input class="form-control" id="street" type="text" name="street" required maxlength="50"/>
                            </div>
                            <div class="col">
                                <label class="form-label" for="city">City</label>
                                <input class="form-control" id="city" type="text" name="city" required maxlength="25"/>
                            </div>
                            <div class="col">
                                <label class="form-label" for="state">State</label>
                                <select class= "form-select" name="state" id="state">
                                    <option value=" ">Select</option>
                                    <option value="AL">Alabama</option>
                                    <option value="AK">Alaska</option>
                                    <option value="AZ">Arizona</option>
                                    <option value="AR">Arkansas</option>
                                    <option value="CA">California</option>
                                    <option value="CO">Colorado</option>
                                    <option value="CT">Connecticut</option>
                                    <option value="DE">Delaware</option>
                                    <option value="DC">District Of Columbia</option>
                                    <option value="FL">Florida</option>
                                    <option value="GA">Georgia</option>
                                    <option value="HI">Hawaii</option>
                                    <option value="ID">Idaho</option>
                                    <option value="IL">Illinois</option>
                                    <option value="IN">Indiana</option>
                                    <option value="IA">Iowa</option>
                                    <option value="KS">Kansas</option>
                                    <option value="KY">Kentucky</option>
                                    <option value="LA">Louisiana</option>
                                    <option value="ME">Maine</option>
                                    <option value="MD">Maryland</option>
                                    <option value="MA">Massachusetts</option>
                                    <option value="MI">Michigan</option>
                                    <option value="MN">Minnesota</option>
                                    <option value="MS">Mississippi</option>
                                    <option value="MO">Missouri</option>
                                    <option value="MT">Montana</option>
                                    <option value="NE">Nebraska</option>
                                    <option value="NV">Nevada</option>
                                    <option value="NH">New Hampshire</option>
                                    <option value="NJ">New Jersey</option>
                                    <option value="NM">New Mexico</option>
                                    <option value="NY">New York</option>
                                    <option value="NC">North Carolina</option>
                                    <option value="ND">North Dakota</option>
                                    <option value="OH">Ohio</option>
                                    <option value="OK">Oklahoma</option>
                                    <option value="OR">Oregon</option>
                                    <option value="PA">Pennsylvania</option>
                                    <option value="RI">Rhode Island</option>
                                    <option value="SC">South Carolina</option>
                                    <option value="SD">South Dakota</option>
                                    <option value="TN">Tennessee</option>
                                    <option value="TX">Texas</option>
                                    <option value="UT">Utah</option>
                                    <option value="VT">Vermont</option>
                                    <option value="VA">Virginia</option>
                                    <option value="WA">Washington</option>
                                    <option value="WV">West Virginia</option>
                                    <option value="WI">Wisconsin</option>
                                    <option value="WY">Wyoming</option>
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label" for="zip">Zip Code</label>
                                <input class="form-control" id="zip" type="number" name="zip">
                            </div>
                        </div>
        
                        <div class="row mb-3 g-3">
                            <div class="col-6">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" type="email" id="email" name="email" required />
                            </div>
                            
                            <div class="col-6">
                                <label class="form-label" for="email">Confirm Email</label>
                                <input class="form-control" type="email" id="confirm_email" name="confirm_email" required />
                            </div>
        
                            <div class="col">
                                <label class="form-label" for="username">Username</label>
                                <input class="form-control" type="text" name="username" required maxlength="30" />
                            </div>
                        </div>
        
        
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label" for="pw">Password</label>
                                <input class="form-control" type="password" id="pw" name="password" required minlength="8" />
                            </div>
                            <div class="col">
                                <label class="form-label" for="confirm">Confirm password</label>
                                <input class="form-control" type="password" id="confirm_pw" name="confirm" required minlength="8" />
                            </div>
                        </div>
                        <input type="submit" class="mt-3 btn btn-primary" value="Submit" />
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
