import AuthController from './AuthController'
import ForgotPasswordController from './ForgotPasswordController'
import DashboardController from './DashboardController'
import UserController from './UserController'
import ResourceScheduleController from './ResourceScheduleController'
import ActionBatchController from './ActionBatchController'
import ActionApplicantController from './ActionApplicantController'
import ActionApplicantProgrammingLanguageController from './ActionApplicantProgrammingLanguageController'
import IntermediateProjectController from './IntermediateProjectController'
import Settings from './Settings'
const Controllers = {
    AuthController: Object.assign(AuthController, AuthController),
ForgotPasswordController: Object.assign(ForgotPasswordController, ForgotPasswordController),
DashboardController: Object.assign(DashboardController, DashboardController),
UserController: Object.assign(UserController, UserController),
ResourceScheduleController: Object.assign(ResourceScheduleController, ResourceScheduleController),
ActionBatchController: Object.assign(ActionBatchController, ActionBatchController),
ActionApplicantController: Object.assign(ActionApplicantController, ActionApplicantController),
ActionApplicantProgrammingLanguageController: Object.assign(ActionApplicantProgrammingLanguageController, ActionApplicantProgrammingLanguageController),
IntermediateProjectController: Object.assign(IntermediateProjectController, IntermediateProjectController),
Settings: Object.assign(Settings, Settings),
}

export default Controllers