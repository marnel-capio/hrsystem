import applications from './applications'
import requisitions from './requisitions'
import projects from './projects'
import applicants from './applicants'
const intermediate = {
    applications: Object.assign(applications, applications),
requisitions: Object.assign(requisitions, requisitions),
projects: Object.assign(projects, projects),
applicants: Object.assign(applicants, applicants),
}

export default intermediate