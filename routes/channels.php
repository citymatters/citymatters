<?php

/*
 * Copyright (C) 2018 city_matters. All rights reserved.
 */

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
