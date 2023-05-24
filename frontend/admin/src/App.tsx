import {Admin, EditGuesser, Layout, ListGuesser, Resource, CustomRoutes} from "react-admin";
import dataProvider from "./dataProvider";
import {authProvider} from "./authProvider";
import { Route } from "react-router-dom";


import projects from "./pages/projects";
import jobs from "./pages/jobs";
import sections from "./pages/sections";
import skills from "./pages/skills";
import educations from "./pages/educations";

import {
  Group as UserIcon,
} from "@mui/icons-material";
import MyMenu from "./components/MyMenu";
import Configuration from "./pages/configuration/Configuration";

const MyLayout = (props: any) => <Layout {...props} menu={MyMenu} />

const App = () => {
    return (
        <Admin
            title="Admin panel"
            basename='/admin'
            dataProvider={dataProvider}
            authProvider={authProvider}
            layout={MyLayout}
            requireAuth
        >
            {/*<Resource name="home" />*/}
            {/*<Resource name="about" />*/}
            <Resource name="projects" {...projects} />
            <Resource name="jobs" {...jobs} />
            <Resource name="educations" {...educations} />
            <Resource name="sections" options={{label: 'Skill Sections'}} {...sections} />
            <Resource name="skills" {...skills}/>
            <Resource icon={UserIcon} name="users" options={{label: 'Users'}} list={ListGuesser} edit={EditGuesser}/>
            <CustomRoutes>
              <Route path="/configuration" element={<Configuration />} />
            </CustomRoutes>
            {/*<Resource name="tags"/>*/}

        </Admin>
    );
};
export default App;
