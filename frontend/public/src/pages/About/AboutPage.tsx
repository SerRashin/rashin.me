import {useState, useEffect, CSSProperties} from 'react';
import ReactMarkdown from 'react-markdown';
import { Container, Col, Row } from 'react-bootstrap';
import { Fade } from 'react-awesome-reveal';
import ApiService, {Property} from "../../services/ApiService";
import FallbackSpinner from "../../components/FallbackSpinner";
import Header from "../../components/Header";

const styles: {[key: string]: CSSProperties} = {
  introTextContainer: {
    margin: 10,
    flexDirection: 'column',
    whiteSpace: 'pre-wrap',
    textAlign: 'left',
    fontSize: '1.2em',
    fontWeight: 500,
  },
  introImageContainer: {
    margin: 10,
    justifyContent: 'center',
    alignItems: 'center',
    display: 'flex',
  },
  imgStyle: {
    maxHeight: '240px',
    maxWidth: '240px',
  }
};

interface AboutProps {
  header: string,
}

const About = ({ header }: AboutProps) => {
  const [userPhoto, setUserPhoto] = useState<string|null>(null);
  const [about, setAbout] = useState<string>('');
  const [isLoading, setIsLoading] = useState<boolean>(true);

  const parseIntro = (text: string) => (
    <ReactMarkdown
      children={text}
    />
  );

  useEffect(() => {
    ApiService.getConfiguration([
      'userPhoto',
      'about',
    ])
      .then((properties: Property[]) => {
        properties.map((property: Property) => {
          switch (property.key) {
            case 'userPhoto':
              ApiService.getFile(parseInt(property.value))
                .then((file) => {
                  setUserPhoto(file.path + file.name);
                })
              break;
            case 'about':
              setAbout(property.value)
              break;
          }
          setIsLoading(false)
        })
      });
  }, []);

  return (
    <>
      <Header title={header} />
      <div className="section-content-container">
        <Container>
          {isLoading === false
            ? (
              <Fade>
                <Row>
                  <Col style={styles.introTextContainer}>
                    {parseIntro(about)}
                  </Col>
                  {userPhoto != null
                    ? (
                      <Col style={styles.introImageContainer}>
                        <img src={userPhoto} alt="profile" style={styles.imgStyle}/>
                      </Col>
                    ) : null}
                </Row>
              </Fade>
            )
            : <FallbackSpinner />}
        </Container>
      </div>
    </>
  );
}

export default About;
