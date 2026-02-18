import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::index
* @see [unknown]:0
* @route '/action/schedules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/action/schedules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::index
* @see [unknown]:0
* @route '/action/schedules'
*/
index.url = (options?: RouteQueryOptions) => {




    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::index
* @see [unknown]:0
* @route '/action/schedules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::index
* @see [unknown]:0
* @route '/action/schedules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::index
* @see [unknown]:0
* @route '/action/schedules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::index
* @see [unknown]:0
* @route '/action/schedules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::index
* @see [unknown]:0
* @route '/action/schedules'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see [unknown]:0
* @route '/action/schedules/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/action/schedules/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see [unknown]:0
* @route '/action/schedules/create'
*/
create.url = (options?: RouteQueryOptions) => {




    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see [unknown]:0
* @route '/action/schedules/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see [unknown]:0
* @route '/action/schedules/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see [unknown]:0
* @route '/action/schedules/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see [unknown]:0
* @route '/action/schedules/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::create
* @see [unknown]:0
* @route '/action/schedules/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see [unknown]:0
* @route '/action/schedules'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/action/schedules',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see [unknown]:0
* @route '/action/schedules'
*/
store.url = (options?: RouteQueryOptions) => {




    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see [unknown]:0
* @route '/action/schedules'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see [unknown]:0
* @route '/action/schedules'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::store
* @see [unknown]:0
* @route '/action/schedules'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::show
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
export const show = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/action/schedules/{schedule}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::show
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
show.url = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { schedule: args }
    }


    if (Array.isArray(args)) {
        args = {
            schedule: args[0],
        }
    }

    args = applyUrlDefaults(args)


    const parsedArgs = {
        schedule: args.schedule,
    }

    return show.definition.url
            .replace('{schedule}', parsedArgs.schedule.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::show
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
show.get = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::show
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
show.head = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::show
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
const showForm = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::show
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
showForm.get = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::show
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
showForm.head = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::edit
* @see [unknown]:0
* @route '/action/schedules/{schedule}/edit'
*/
export const edit = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/action/schedules/{schedule}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::edit
* @see [unknown]:0
* @route '/action/schedules/{schedule}/edit'
*/
edit.url = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { schedule: args }
    }


    if (Array.isArray(args)) {
        args = {
            schedule: args[0],
        }
    }

    args = applyUrlDefaults(args)


    const parsedArgs = {
        schedule: args.schedule,
    }

    return edit.definition.url
            .replace('{schedule}', parsedArgs.schedule.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::edit
* @see [unknown]:0
* @route '/action/schedules/{schedule}/edit'
*/
edit.get = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::edit
* @see [unknown]:0
* @route '/action/schedules/{schedule}/edit'
*/
edit.head = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::edit
* @see [unknown]:0
* @route '/action/schedules/{schedule}/edit'
*/
const editForm = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::edit
* @see [unknown]:0
* @route '/action/schedules/{schedule}/edit'
*/
editForm.get = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::edit
* @see [unknown]:0
* @route '/action/schedules/{schedule}/edit'
*/
editForm.head = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::update
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
export const update = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/action/schedules/{schedule}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::update
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
update.url = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { schedule: args }
    }


    if (Array.isArray(args)) {
        args = {
            schedule: args[0],
        }
    }

    args = applyUrlDefaults(args)


    const parsedArgs = {
        schedule: args.schedule,
    }

    return update.definition.url
            .replace('{schedule}', parsedArgs.schedule.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::update
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
update.put = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::update
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
update.patch = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::update
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
const updateForm = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::update
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
updateForm.put = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::update
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
updateForm.patch = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::destroy
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
export const destroy = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/action/schedules/{schedule}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::destroy
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
destroy.url = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { schedule: args }
    }


    if (Array.isArray(args)) {
        args = {
            schedule: args[0],
        }
    }

    args = applyUrlDefaults(args)


    const parsedArgs = {
        schedule: args.schedule,
    }

    return destroy.definition.url
            .replace('{schedule}', parsedArgs.schedule.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::destroy
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
destroy.delete = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::destroy
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
const destroyForm = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Settings\ResourceScheduleController::destroy
* @see [unknown]:0
* @route '/action/schedules/{schedule}'
*/
destroyForm.delete = (args: { schedule: string | number } | [schedule: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm



const schedules = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default schedules