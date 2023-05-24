import '../App.css';

interface HeaderProps {
  title: string,
}

const Header = ({ title }: HeaderProps) => {
  return <div className="header">{title}</div>;
}

export default Header;
