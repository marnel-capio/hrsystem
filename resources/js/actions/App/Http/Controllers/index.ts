import AuthController from './AuthController'
import ResourceScheduleController from './ResourceScheduleController'
import DashboardController from './DashboardController'
import Settings from './Settings'


const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
    ResourceScheduleController: Object.assign(ResourceScheduleController, ResourceScheduleController),
    DashboardController: Object.assign(DashboardController, DashboardController),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers