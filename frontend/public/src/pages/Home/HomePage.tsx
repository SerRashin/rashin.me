import {useState, useEffect, CSSProperties} from 'react';
import Typewriter from 'typewriter-effect';
import { Fade } from 'react-awesome-reveal';
import ApiService, {Property} from "../../services/ApiService";
import Social from "../../components/Social";
import FallbackSpinner from "../../components/FallbackSpinner";

const styles: {[key: string]: CSSProperties} = {
  nameStyle: {
    fontSize: '5em',
  },
  inlineChild: {
    display: 'inline-block',
  },
  mainContainer: {
    height: '100%',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'center',
    alignItems: 'center',
  },
};

const HomePage = () => {
  const [userName, setUserName] = useState<string>('');
  const [roles, setRoles] = useState<string[]>([]);
  const [githubLink, setGithubLink] = useState<string>('');
  const [linkedinLink, setLinkedinLink] = useState<string>('');
  const [emailAddress, setEmailAddress] = useState<string>('');
  const [isLoading, setIsLoading] = useState<boolean>(true);

  useEffect(() => {
    ApiService.getConfiguration([
      'userName',
      'roles',
      'githubLink',
      'linkedinLink',
      'emailAddress',
    ])
      .then((properties: Property[]) => {
        properties.map((property: Property) => {
          switch (property.key) {
            case 'userName':
              setUserName(property.value)
              break;
            case 'roles':
              setRoles(JSON.parse(property.value))
              break;
            case 'githubLink':
              setGithubLink(property.value)
              break;
            case 'linkedinLink':
              setLinkedinLink(property.value)
              break;
            case 'emailAddress':
              setEmailAddress(property.value)
              break;
          }
          setIsLoading(false)
        })
      });
  }, []);

  return isLoading == false ? (
    <Fade cascade>
      <div style={styles.mainContainer}>
        <h1 style={styles.nameStyle}>{userName}</h1>
        <div style={{ flexDirection: 'row' }}>
          <h2 style={styles.inlineChild}>I&apos;m&nbsp;</h2>
          <Typewriter
            options={{
              loop: true,
              autoStart: true,
              strings: roles,
            }}
          />
        </div>
        <Social
          github={githubLink}
          linkedin={linkedinLink}
          email={emailAddress}
        />
      </div>
    </Fade>
  ) : <FallbackSpinner />;
}

export default HomePage;
