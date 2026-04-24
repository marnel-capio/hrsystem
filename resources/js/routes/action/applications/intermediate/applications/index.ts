import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1202
 * @route '/action/applications/intermediate/applications/{application}/send-notification'
 */
export const sendNotification = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

sendNotification.definition = {
    methods: ["post"],
    url: '/action/applications/intermediate/applications/{application}/send-notification',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1202
 * @route '/action/applications/intermediate/applications/{application}/send-notification'
 */
sendNotification.url = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { application: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    application: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        application: args.application,
                }

    return sendNotification.definition.url
            .replace('{application}', parsedArgs.application.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1202
 * @route '/action/applications/intermediate/applications/{application}/send-notification'
 */
sendNotification.post = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendNotification.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1202
 * @route '/action/applications/intermediate/applications/{application}/send-notification'
 */
    const sendNotificationForm = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: sendNotification.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\IntermediateApplicationController::sendNotification
 * @see app/Http/Controllers/IntermediateApplicationController.php:1202
 * @route '/action/applications/intermediate/applications/{application}/send-notification'
 */
        sendNotificationForm.post = (args: { application: string | number } | [application: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: sendNotification.url(args, options),
            method: 'post',
        })
    
    sendNotification.form = sendNotificationForm
const applications = {
    sendNotification: Object.assign(sendNotification, sendNotification),
}

export default applications