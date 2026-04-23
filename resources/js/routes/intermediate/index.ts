import applications from './applications'
import projects from './projects'
import requisitions from './requisitions'
import applicants from './applicants'
const intermediate = {
    applications: Object.assign(applications, applications),
projects: Object.assign(projects, projects),
requisitions: Object.assign(requisitions, requisitions),
applicants: Object.assign(applicants, applicants),
}

export default intermediate