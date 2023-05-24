import { Menu } from 'react-admin';
import SettingsApplicationsIcon from '@mui/icons-material/SettingsApplications';
import React from "react";

const MyMenu = () => {
  return (
    <Menu>
      <Menu.ResourceItem name="projects"/>
      <Menu.ResourceItem name="jobs"/>
      <Menu.ResourceItem name="educations"/>
      <Menu.ResourceItem name="sections"/>
      <Menu.ResourceItem name="skills"/>
      <Menu.ResourceItem name="users"/>
      <Menu.Item to="/configuration" primaryText="Configuration" leftIcon={<SettingsApplicationsIcon/>}/>
    </Menu>
  );
}

export default MyMenu;
