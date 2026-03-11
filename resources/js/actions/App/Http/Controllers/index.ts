import AuthController from './AuthController'
import ForgotPasswordController from './ForgotPasswordController'
import DashboardController from './DashboardController'
import ActionApplicantController from './ActionApplicantController'
import UserController from './UserController'
import ResourceScheduleController from './ResourceScheduleController'
import ActionBatchController from './ActionBatchController'
import Settings from './Settings'
const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
ForgotPasswordController: Object.assign(ForgotPasswordController, ForgotPasswordController),
DashboardController: Object.assign(DashboardController, DashboardController),
ActionApplicantController: Object.assign(ActionApplicantController, ActionApplicantController),
UserController: Object.assign(UserController, UserController),
ResourceScheduleController: Object.assign(ResourceScheduleController, ResourceScheduleController),
ActionBatchController: Object.assign(ActionBatchController, ActionBatchController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers